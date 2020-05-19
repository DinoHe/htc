<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base;
use App\Http\Models\Admins;
use App\Http\Models\Permissions;
use App\Http\Models\Roles;
use Illuminate\Support\Facades\Hash;

class Admin extends Base
{
    public function list()
    {
        $admins = Admins::all();
        return view('admin.admin.list',['admins'=>$admins]);
    }

    public function accountStop($id)
    {
        Admins::where('id',$id)->update(['blocked'=>Admins::ACCOUNT_BLOCKED]);
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    public function accountOpen($id)
    {
        Admins::where('id',$id)->update(['blocked'=>Admins::ACCOUNT_ON]);
        return $this->dataReturn(['status'=>0,'message'=>'操作成功']);
    }

    public function add()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $admin = Admins::where('account',$data['account'])->first();
            if (!empty($admin)){
                return $this->dataReturn(['status'=>1,'message'=>'账号已存在']);
            }
            Admins::create([
                'account' => $data['account'],
                'password' => Hash::make($data['password']),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'weixin' => $data['weixin'],
                'role_id' => $data['role']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        $roles = Roles::all();
        return view('admin.admin.add',['roles'=>$roles]);
    }

    public function edit()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $res = Admins::where('id',$data['id'])->update([
                'account' => $data['account'],
                'password' => Hash::make($data['password']),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'weixin' => $data['weixin'],
                'role_id' => $data['role']
            ]);
            if ($res){
                return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
            }else{
                return $this->dataReturn(['status'=>10001,'message'=>'添加失败']);
            }
        }
        $admin = Admins::find($this->request->input('id'));
        $roles = Roles::all();
        return view('admin.admin.edit',['admin'=>$admin,'roles'=>$roles]);
    }

    public function del()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Admins::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function role()
    {
        $roles = Roles::all();
        foreach ($roles as $r) {
            $admins = $r->admins;
            $accounts = '';
            foreach ($admins as $admin) {
                if ($accounts == ''){
                    $accounts = $admin->name;
                }else{
                    $accounts .= '，' . $admin->name;
                }
            }
            $r->user = $accounts;

            $permissionIds = $r->permission;
            $permissionIds = explode(',',$permissionIds)?:[$permissionIds];
            $permissionTittles = '';
            if ($permissionIds[0] == 0){
                $r->permission = '所有权限';
            }else{
                $permissions = Permissions::find($permissionIds);
                foreach ($permissions as $permission) {
                    if ($permissionTittles == ''){
                        $permissionTittles = $permission->tittle.'：';
                    }else{
                        if ($permission->pid == 0){
                            $permissionTittles .= '；'.$permission->tittle.'： ';
                        }else{
                            $permissionTittles .= '，'.$permission->tittle;
                        }
                    }
                }
                $r->permission = preg_replace('/\s，/','',$permissionTittles);
            }
        }
        return view('admin.admin.role',['roles'=>$roles]);
    }
}
