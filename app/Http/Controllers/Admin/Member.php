<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\MemberLevels;
use App\Http\Models\Members;
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
            if (!empty($data['account'])) $where['member_id'] = Members::where('phone',$data['account'])->first()->id;
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
        $realName = RealNameAuths::find($data['id']);
        return view('admin.member.realname-edit',['realName'=>$realName]);
    }

    public function realNameCheck()
    {
        $data = $this->request->input();
        $info['auth_status'] = RealNameAuths::AUTH_CHECK_FAIL;
        if ($data['auth_status'] == 1) $info['auth_status'] = RealNameAuths::AUTH_SUCCESS;
        RealNameAuths::where('id',$data['id'])->update($info);
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }
}
