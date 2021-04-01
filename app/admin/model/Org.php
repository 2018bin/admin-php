<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019-06-30
 * Time: 14:33
 */

namespace app\admin\model;

use app\admin\model\Admin as AdminModel;
/**
 * Class Admin
 * @package app\admin\model
 */
class Org extends BaseModel
{
    public static function childrenOrgDelete($parent_id){
        $orgList = self::where('parent_id', $parent_id)->field('id')->select();
        $array=array();
        foreach($orgList as $k=>$v){
            $childrenId=self::childrenOrgDelete($v['id']);
            $array = array_merge($array,$childrenId );
            array_push($array,$v['id']) ;
        }
        return $array;

    }


    /**
     * 一对一关联表org，求父机构
     * @return \think\model\relation\HasOne
     */
    public function org()
    {
        return $this->hasOne(self::class,'id','parent_id');
    }

    /**一对一关联表admin
     * @return \think\model\relation\HasOne
     */
    public function admin()
    {
        return $this->hasOne(AdminModel::class,'org_id','id');
    }


}