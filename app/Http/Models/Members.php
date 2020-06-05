<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Members extends Authenticatable
{
    use Notifiable;

    //激活状态
    const ACTIVATED = 0;
    const ACTIVATE_NO = 1;
    const BLOCKED_TMP = 2;
    const BLOCKED_FOREVER = 3;

    protected $fillable = [
        'phone','password','safe_password','level_id','parentid','activated','describes','credit','invite'
    ];

    public function level()
    {
        return $this->belongsTo('App\Http\Models\MemberLevels','level_id');
    }

    public function asset()
    {
        return $this->hasOne('App\Http\Models\Assets','member_id');
    }

    public function realNameAuth()
    {
        return $this->hasOne('App\Http\Models\RealNameAuths','member_id');
    }

    public function getAccountStatus($status)
    {
        switch ($status){
            case self::ACTIVATED:
                return '已激活';
            case self::ACTIVATE_NO:
                return '未激活';
            case self::BLOCKED_TMP:
                return '临时冻结';
            case self::BLOCKED_FOREVER:
                return '永久冻结';
        }
    }

    public function getSubordinates($id)
    {
        $subordinates = self::where('parentid',$id)->get();
        $subordinatesArray = [];
        $realNameAuthed = 0;
        if (!$subordinates->isEmpty()){
            foreach ($subordinates as $subordinate) {
                $realNameAuth = $subordinate->realNameAuth;
                if (!empty($realNameAuth) && $realNameAuth->auth_status == RealNameAuths::AUTH_SUCCESS){
                    $subordinate->realNameStatus = '已认证';
                    $realNameAuthed++;
                }else{
                    $subordinate->realNameStatus = '未认证';
                }
                $subordinate->team_total = count($this->getChildNodes($subordinate->id));
                $subordinate->memberLevel = $subordinate->level->level_name;
                $subordinate->subordinatesCount = self::where('parentid',$subordinate->id)->count();
                array_push($subordinatesArray,$subordinate);
            }
        }
        return array($subordinatesArray,$realNameAuthed);
    }

    private function getChildNodes($id)
    {
        $childNodes = Members::where('parentid',$id)->get();
        static $child = [];
        if (!$childNodes->isEmpty()){
            foreach ($childNodes as $childNode) {
                $child[] = $childNode;
                $this->getChildNodes($childNode->id);
            }
        }

        return $child;
    }

}
