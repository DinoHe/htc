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
        //取出角色所属用户
        foreach ($roles as $r) {
            $admins = $r->admins;
            $accounts = '';
            foreach ($admins as $admin) {
                if ($accounts == ''){
                    $accounts = $admin->account;
                }else{
                    $accounts .= '，' . $admin->account;
                }
            }
            $r->user = $accounts;

            $permissionIds = $r->permission;
            $permissionIds = explode(',',$permissionIds)?:[$permissionIds];
            $permissionTittles = '';
            if ($permissionIds[0] == 0){
                $r->permission = '所有权限';
            }else{
                //权限ID转换成名称输出
                foreach ($permissionIds as $permissionId) {
                    $permission = Permissions::find($permissionId);
                    if (empty($permission)) continue;
                    if ($permissionTittles == ''){
                        $permissionTittles = $permission->tittle.'：查看';
                    }elseif ($permission->pid == 0){
                            $permissionTittles .= '；'.$permission->tittle.'：查看';
                    }else{
                        $permissionTittles .= '，'.$permission->tittle;
                    }
                }
                $r->permission = $permissionTittles;
            }
        }

        return view('admin.admin.role',['roles'=>$roles]);
    }

    public function roleAdd()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $p = implode(',',$data['permission'])?:$data['permission'];
            Roles::create([
                'name' => $data['roleName'],
                'permission' => $p
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        $permissions = Permissions::where('pid',0)->get();
        foreach ($permissions as $permission) {
            $permission->childNodes = Permissions::where('pid',$permission->id)->get();
        }

        return view('admin.admin.role-add',['permissions'=>$permissions]);
    }

    public function roleEdit()
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            $p = implode(',',$data['permission'])?:$data['permission'];
            Roles::where('id',$data['id'])->update([
                'name' => $data['roleName'],
                'permission' => $p
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        $role = Roles::find($this->request->input('id'));
        $permissions = Permissions::where('pid',0)->get();
        foreach ($permissions as $permission) {
            $permission->childNodes = Permissions::where('pid',$permission->id)->get();
        }

        return view('admin.admin.role-edit',['role'=>$role,'permissions'=>$permissions]);
    }

    public function roleDel()
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:$id;
        Roles::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }

    public function permission(Permissions $permissions)
    {
        $permissions = $permissions->getPermissionChildNodes();
        return view('admin.admin.permission',['permissions'=>$permissions]);
    }

    public function permissionAdd(Permissions $permissions)
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            Permissions::create([
                'tittle' => $data['tittle'],
                'url' => $data['url'],
                'pid' => $data['pid']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'添加成功']);
        }
        $permissions = $permissions->getPermissionChildNodes();
        return view('admin.admin.permission-add',['permissions'=>$permissions]);
    }

    public function permissionEdit(Permissions $permissions)
    {
        if ($this->request->isMethod('post')){
            $data = $this->request->input();
            Permissions::where('id',$data['id'])->update([
                'tittle' => $data['tittle'],
                'url' => $data['url'],
                'pid' => $data['pid']
            ]);
            return $this->dataReturn(['status'=>0,'message'=>'修改成功']);
        }
        $permission = Permissions::find($this->request->input('id'));
        $permissionChildNodes = $permissions->getPermissionChildNodes();
        return view('admin.admin.permission-edit',['permission'=>$permission,'permissionChildNodes'=>$permissionChildNodes]);
    }

    public function permissionDel(Permissions $permissions)
    {
        $id = $this->request->input('id');
        $ids = explode(',',$id)?:[$id];
        foreach ($ids as $id) {
            $permissions->deletePermissionChildNodes($id);
        }
        Permissions::destroy($ids);
        return $this->dataReturn(['status'=>0,'message'=>'删除成功']);
    }
}
