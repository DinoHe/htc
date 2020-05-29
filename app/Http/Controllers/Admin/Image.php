<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Images;

class Image extends Base
{
    public function list()
    {
        $images = Images::all();
        return view('admin.image.list',['images'=>$images]);
    }

    public function add()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $file = $this->request->file('img');
            if (empty($file)){
                return $this->dataReturn(['status'=>1,'message'=>'请上传图片']);
            }
            $path = $file->storeAs('public/homeImg',$data['imgTittle'].'.jpg');
            Images::create([
                'type' => $data['type'],
                'src' => substr($path,15)
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        return view('admin.image.add');
    }

    public function edit()
    {
        $data = $this->request->input();
        if ($this->request->isMethod('post')){
            $file = $this->request->file('img');
            if (!empty($file)){
                $path = $file->storeAs('public/homeImg',$data['imgTittle'].'.jpg');
                $info['src'] = substr($path,15);
            }
            $info['type'] = $data['type'];
            Images::where('id',$data['id'])->update($info);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        $image = Images::find($data['id']);
        return view('admin.image.edit',['image'=>$image]);
    }

    public function del()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Images::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }
}
