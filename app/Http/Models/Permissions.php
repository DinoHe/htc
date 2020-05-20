<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{

    protected $fillable = [
        'tittle','url','pid'
    ];

    public function getPermissionChildNodes($id=0, $level=0)
    {
        $permissionNodes = self::where('pid',$id)->get();
        static $permissionNodeArray = [];
        if (!$permissionNodes->isEmpty()){
            foreach ($permissionNodes as $permissionNode) {
                $permissionNode->level = $level;
                array_push($permissionNodeArray,$permissionNode);
                $this->getPermissionChildNodes($permissionNode->id, ++$level);
                $level--;
            }
        }

        return $permissionNodeArray;
    }

    public function deletePermissionChildNodes($id)
    {
        $childNodes = self::where('pid',$id)->get();
        if (!$childNodes->isEmpty()){
            foreach ($childNodes as $childNode) {
                $childNode->delete();
                $this->deletePermissionChildNodes($childNode->id);
            }
        }
    }

}
