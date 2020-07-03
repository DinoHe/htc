<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Coins;
use App\Http\Models\SystemLogs;
use App\Http\Models\SystemNotices;
use App\Http\Models\SystemSettings;

class System extends Base
{

    public function setting()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->all();
            foreach ($data as $k => $d) {
                if ($k != '_token'){
                    SystemSettings::where('tittle',$k)->update(['value'=>$d]);
                }
            }
            return $this->dataReturn(['status'=>0,'message'=>'保存成功']);
        }
        $settings = SystemSettings::all();
        $coin = Coins::orderBy('updated_at','desc')->first();
        return view('admin.system.setting',['settings'=>$settings,'coin'=>$coin]);
    }

    public function advancedSetting()
    {
        $data = $this->request->input();
        Coins::where('id',$data['id'])->update(['price'=>$data['price']*100]);

        return $this->dataReturn(['status'=>0,'message'=>'保存成功']);
    }

    public function sendTest()
    {
        $phone = $this->request->input('phone');
        $msg = parent::sendSMS($phone);
        return $this->dataReturn(['status'=>0,'message'=>$msg]);
    }

    public function notice()
    {
        $notices = SystemNotices::all();

        return view('admin.system.notice',['notices'=>$notices]);
    }

    public function noticeAdd()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            SystemNotices::create([
                'tittle' => $data['tittle'],
                'content' => $data['content']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        return view('admin.system.notice-add');
    }

    public function noticeEdit()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            SystemNotices::where('id',$data['id'])->update([
                'tittle' => $data['tittle'],
                'content' => $data['content']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'修改成功']);
        }

        $notice = SystemNotices::find($this->request->input('id'));
        return view('admin.system.notice-edit',['notice'=>$notice]);
    }

    public function noticeDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        SystemNotices::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function log()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $this->request->flashOnly(['event','date_start','date_end','account','ip']);
            $where = [];
            if ($data['event'] != '0') $where['event'] = $data['event'];
            if (!empty($data['account'])) $where['account'] = $data['account'];
            if (!empty($data['ip'])) $where['ip'] = $data['ip'];
            $logs = SystemLogs::where($where)->whereBetween('created_at',[$data['date_start'],$data['date_end']])->get();

            return view('admin.system.log',['logs'=>$logs]);
        }
        $logs = SystemLogs::whereBetween('created_at',[date('Y-m-d 0:0:0'),date('Y-m-d H:i:s')])->get();

        return view('admin.system.log',['logs'=>$logs]);
    }

    public function logDetails()
    {
        $log = SystemLogs::find($this->request->input('id'));
        return view('admin.system.log-details',['log'=>$log]);
    }

    public function logDestroy()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        SystemLogs::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

}
