<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Assets;
use App\Http\Models\Bills;
use App\Http\Models\BuyActivities;
use App\Http\Models\Ideals;
use App\Http\Models\MemberLevels;
use App\Http\Models\Members;
use App\Http\Models\Miners;
use App\Http\Models\MyMiners;
use App\Http\Models\RealNameAuths;
use Illuminate\Support\Facades\Hash;

class Member extends Base
{

    public function list()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $this->request->flashOnly(['activated','level','date_start','date_end','account','credit']);
            $where = [];
            if ($data['activated'] != '-1') $where['activated'] = $data['activated'];
            if ($data['level'] != '0') $where['level_id'] = $data['level'];
            if (!empty($data['account'])) $where['phone'] = $data['account'];
            if (!empty($data['credit'])) $where['credit'] = $data['credit'];
            $model = Members::where($where);
            if (!empty($data['date_start'])) {
                $date_end = empty($data['date_end'])?date('Y-m-d H:i:s'):$data['date_end'];
                $model = $model->whereBetween('created_at',[$data['date_start'],$date_end]);
            }
            $members = $model->get();
            foreach ($members as $member) {
                $member->levelName = $member->level->level_name;
                $member->status = $member->getAccountStatus($member->activated);
            }
            return view('admin.member.list',['members'=>$members]);
        }

        $members = Members::limit(1000)->get();
        foreach ($members as $member) {
            $member->levelName = $member->level->level_name;
            $member->status = $member->getAccountStatus($member->activated);
        }
        return view('admin.member.list',['members'=>$members]);
    }

    public function edit()
    {
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            $info = [];
            if (!empty($data['password'])) $info['password'] = Hash::make($data['password']);
            if (!empty($data['safe_password'])) $info['safe_password'] = Hash::make($data['safe_password']);
            $info['credit'] = $data['credit'];
            $info['activated'] = $data['activated'];
            Members::where('id',$data['id'])->update($info);
            return $this->dataReturn(['status'=>0,'message'=>'修改成功']);
        }
        $member = Members::find($data['id']);
        return view('admin.member.edit',['member'=>$member]);
    }

    public function del()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Members::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function level()
    {
        $levels = MemberLevels::all();

        return view('admin.member.level',['levels'=>$levels]);
    }

    public function levelAdd()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            MemberLevels::create([
                'level_name' => $data['levelName'],
                'sales_times' => $data['salesTimes']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        return view('admin.member.level-add');
    }

    public function levelEdit()
    {
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            MemberLevels::where('id',$data['id'])->update([
                'level_name' => $data['levelName'],
                'sales_times' => $data['salesTimes']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }

        $level = MemberLevels::find($data['id']);
        return view('admin.member.level-edit',['level'=>$level]);
    }

    public function levelDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        MemberLevels::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function realName()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $this->request->flashOnly(['auth_status','account','weixin','alipay']);
            $where = [];
            if ($data['auth_status'] != '-1') $where['auth_status'] = $data['auth_status'];
            if (!empty($data['account'])) {
                $member = Members::where('phone',$data['account'])->first();
                if (!empty($member)) $where['member_id'] = $member->id;
            }
            if (!empty($data['weixin'])) $where['weixin'] = $data['weixin'];
            if (!empty($data['alipay'])) $where['alipay'] = $data['alipay'];
            $realnames = RealNameAuths::where($where)->get();
            return view('admin.member.realname',['realnames'=>$realnames]);
        }
        $realnames = RealNameAuths::where('auth_status','<>',RealNameAuths::AUTH_SUCCESS)->get();
        return view('admin.member.realname',['realnames'=>$realnames]);
    }

    public function realNameEdit()
    {
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            $info = ['name'=>$data['name'],'idcard'=>$data['idcard'],'weixin'=>$data['weixin'],'alipay'=>$data['alipay']];
            if (!empty($data['bank_name'])) $info['bank_name'] = $data['bank_name'];
            if (!empty($data['bank_card'])) $info['bank_card'] = $data['bank_card'];

            $frontFile = $this->request->file('idcard_front_img');
            $backFile = $this->request->file('idcard_back_img');
            if (!empty($frontFile)){
                if ($frontFile->getSize()/(1024*1024) > 1) return $this->dataReturn(['status'=>1,'message'=>'图片大小不能超过1M']);
                $frontPath = $frontFile->storeAs('public/idcardImg',time().$data['idcard'].'front.jpg');
                $info['idcard_front_img'] = substr($frontPath,6);
            }
            if (!empty($backFile)){
                if ($backFile->getSize()/(1024*1024) > 1) return $this->dataReturn(['status'=>1,'message'=>'图片大小不能超过1M']);
                $backPath = $backFile->storeAs('public/idcardImg',time().$data['idcard'].'back.jpg');
                $info['idcard_back_img'] = substr($backPath,6);
            }

            RealNameAuths::where('id',$data['id'])->update($info);
            return $this->dataReturn(['status'=>0,'message'=>'修改成功']);
        }
        $realName = RealNameAuths::find($data['id']);
        return view('admin.member.realname-edit',['realName'=>$realName]);
    }

    /**
     * 实名认证审核
     * @return false|string
     */
    public function realNameCheck()
    {
        $data = $this->request->input();
        $info['auth_status'] = RealNameAuths::AUTH_CHECK_FAIL;
        if ($data['auth_status'] == 1) $info['auth_status'] = RealNameAuths::AUTH_SUCCESS;
        RealNameAuths::where('id',$data['id'])->update($info);
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    public function realNameDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        RealNameAuths::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function assets()
    {
        $model = Assets::where(null);
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $this->request->flashOnly(['account','balanceMin','buyMin']);
            if (!empty($data['account'])) {
                $member = Members::where('phone',$data['account'])->first();
                if (!empty($member)){
                    $model = $model->where('member_id',$member->id);
                }
            }
            if (!empty($data['balanceMin'])) {
                $model = $model->where('balance','>=',$data['balanceMin']*100);
            }
            if (!empty($data['buyMin'])) {
                $model = $model->where('buy_total','>=',$data['buyMin']);
            }
        }
        $assets = $model->get();
        return view('admin.member.assets',['assets'=>$assets]);
    }

    public function assetsRecharge()
    {
        $data = $this->request->input();
        $assets = Assets::find($data['id']);
        if ($this->request->isMethod('post')){
            if (empty($data['balance']) && empty($data['blockedAssets']) && empty($data['buyTotal'])){
                return $this->dataReturn(['status'=>-1,'message'=>'请输入充值数量']);
            }
            if (!empty($data['balance'])) $assets->balance += $data['balance'];
            if (!empty($data['blockedAssets'])) $assets->blocked_assets += $data['blockedAssets'];
            if (!empty($data['buyTotal'])) $assets->buy_total += $data['buyTotal'];
            $assets->save();
            return $this->dataReturn(['status'=>0,'message'=>'充值成功']);
        }

        return view('admin.member.assets-recharge',['assets'=>$assets]);
    }

    /**
     * 冻结HTC资产
     * @return false|string
     */
    public function assetsBlock()
    {
        $data = $this->request->input();
        $assets = Assets::find($data['id']);
        $assets->balance -= $data['blockNumber'];
        $assets->blocked_assets += $data['blockNumber'];
        $assets->save();
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    /**
     * 资产统计
     * @return false|string
     */
    public function assetsSum()
    {
        $model = Assets::where(null);
        $data = $this->request->input();
        if (!empty($data['account'])) {
            $id = Members::where('phone',$data['account'])->first()->id;
            $model = $model->where('member_id',$id);
        }
        if (!empty($data['balanceMin'])) {
            $model = $model->where('balance','>=',$data['balanceMin']*100);
        }
        if (!empty($data['buyMin'])) {
            $model = $model->where('buy_total','>=',$data['buyMin']);
        }
        $assets = $model->get();
        $balanceSum = 0;
        $blockedSum = 0;
        $buySum = 0;
        foreach ($assets as $asset) {
            $balanceSum += $asset->balance;
            $blockedSum += $asset->blocked_assets;
            $buySum += $asset->buy_total;
        }
        return $this->dataReturn(['status'=>0,'balanceSum'=>round($balanceSum,2),
            'blockedSum'=>round($blockedSum,2),'buySum'=>$buySum]);
    }

    public function assetsDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Assets::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function myMiner()
    {
        $model = MyMiners::where(null);
        $miners = Miners::all();
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            $this->request->flashOnly(['account','minerType']);
            if (!empty($data['account'])) {
                $member = Members::where('phone',$data['account'])->first();
                if (!empty($member)){
                    $model = $model->where('member_id',$member->id);
                }
            }
            if ($data['minerType'] != '-1') {
                $model = $model->where('miner_id',$data['minerType']);
            }
        }

        $myminers = $model->get();
        parent::initMiners($myminers);
        return view('admin.member.myminer',['myminers'=>$myminers,'miners'=>$miners]);
    }

    /**
     * 结束矿机运行
     * @param $id
     * @return false|string
     */
    public function myMinerStop($id)
    {
        $myMiner = MyMiners::find($id);
        if (!empty($myMiner)){
            $myMiner->run_status = MyMiners::RUN_FINISHED;
            $myMiner->save();
        }
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    /**
     * 赠送矿机
     * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function myMinerAdd()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $member = Members::where('phone',$data['account'])->first();
            if (empty($member)){
                return $this->dataReturn(['status'=>1,'message'=>'账户不存在']);
            }
            $miner = Miners::find($data['minerType']);
            for ($i=0;$i<$data['number'];$i++){
                MyMiners::create([
                    'member_id' => $member->id,
                    'miner_id' => $data['minerType'],
                    'miner_tittle' => $miner->tittle,
                    'hashrate' => $miner->hashrate,
                    'total_dig' => $miner->total_dig,
                    'runtime' => $miner->runtime,
                    'nph' => $miner->nph
                ]);
            }
            return $this->dataReturn(['status'=>0,'message'=>'赠送成功']);
        }
        $miners = Miners::all();
        return view('admin.member.myminer-add',['miners'=>$miners]);
    }

    public function myMinerDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        MyMiners::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function bill()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $this->request->flashOnly(['date_start','date_end','account']);
            $model = Bills::where(null);
            if (!empty($data['date_start']) && !empty($data['date_end'])){
                $model = $model->WhereBetween('created_at',[$data['date_start'],$data['date_end']]);
            }
            if (!empty($data['account'])){
                $memberId = Members::where('phone',$data['account'])->first()->id;
                $model = $model->where('member_id',$memberId);
            }
            $bills = $model->get();
            return view('admin.member.bill',['bills'=>$bills]);
        }
        $bills = Bills::where('created_at','>=',date('Y-m-d 0:0:0'))->get();
        return view('admin.member.bill',['bills'=>$bills]);
    }

    public function billDestroy()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Bills::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function team()
    {
        $teams = [];
        if ($this->request->isMethod('post')){
            $account = $this->request->input('account');
            $this->request->flashOnly(['account']);
            $member = Members::where('phone',$account)->first();
            if (!empty($member)){
                $teams = $member->getSubordinates($member->id)[0];
            }
        }
        return view('admin.member.team',['teams'=>$teams]);
    }

    public function activity()
    {
        $activities = BuyActivities::all();
        foreach ($activities as $activity) {
            $members = Members::find($activity->reward_member);
            if (!$members->isEmpty()){
                $rewardMembers = [];
                foreach ($members as $member) {
                    array_push($rewardMembers,$member->phone);
                }
                $activity->rewardMembers = implode(',',$rewardMembers);
            }
        }
        return view('admin.member.activity',['activities'=>$activities]);
    }

    public function activityAdd()
    {
        $miners = Miners::all();
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            BuyActivities::create([
                'buy_number' => $data['buyNumber'],
                'reward_leader_miner_type' => $data['minerType'],
                'reward_leader_miner_number' => $data['number']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        return view('admin.member.activity-add',['miners'=>$miners]);
    }

    public function activityEdit()
    {
        $miners = Miners::all();
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            BuyActivities::where('id',$data['id'])->update([
                'buy_number' => $data['buyNumber'],
                'reward_leader_miner_type' => $data['minerType'],
                'reward_leader_miner_number' => $data['number'],
                'reward_member' => $data['rewardMembers']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'编辑成功']);
        }
        $activity = BuyActivities::find($data['id']);
        $members = Members::find($activity->reward_member);
        if (!$members->isEmpty()){
            $rewardMembers = [];
            foreach ($members as $member) {
                array_push($rewardMembers,$member->phone);
            }
            $activity->rewardMembers = $rewardMembers;
            $activity->rewardMemberStr = implode(',',$rewardMembers);
        }
        return view('admin.member.activity-edit',['miners'=>$miners,'activity'=>$activity]);
    }

    public function activityDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        BuyActivities::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function ideal()
    {
        $model = Ideals::where(null);
        if ($this->request->isMethod('post')){
            $member = Members::where('phone',$this->request->input('account'))->first();
            if (!empty($member)){
                $model = $model->where('account',$member->phone);
            }
        }
        $ideals = $model->get();
        return view('admin.member.ideal',['ideals'=>$ideals]);
    }

    public function idealDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Ideals::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

}
