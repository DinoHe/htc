<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Miners;

class Miner extends Base
{

    public function list()
    {
        $miners = Miners::all();
        return view('admin.miner.list',['miners'=>$miners]);
    }

    public function add()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            Miners::create([
                'tittle' => $data['tittle'],
                'coin_number' => $data['price'],
                'hashrate' => $data['hashrate'],
                'total_dig' => $data['totalDig'],
                'runtime' => $data['runtime'],
                'nph' => $data['nph'],
                'rent_max' => $data['rentMax']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        return view('admin.miner.add');
    }

    public function edit()
    {
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            Miners::where('id',$data['id'])->update([
                'tittle' => $data['tittle'],
                'coin_number' => $data['price'],
                'hashrate' => $data['hashrate'],
                'total_dig' => $data['totalDig'],
                'runtime' => $data['runtime'],
                'nph' => $data['nph'],
                'rent_max' => $data['rentMax']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'修改成功']);
        }
        $miner = Miners::find($data['id']);
        return view('admin.miner.edit',['miner'=>$miner]);
    }

    public function del()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Miners::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }
}
