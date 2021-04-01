<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/15
 * Time: 17:48
 */

namespace app\admin\controller;


use app\admin\common\BaseOauthController;
use app\admin\model\Org as OrgModel;
use app\admin\model\Permission as PermissionModel;
use app\admin\model\Role as RoleModel;
use app\common\StatusEnum;

class Role extends BaseOauthController
{

    /**
     * 获取路由权限
     */
    public function getRoutes(){

        $permission=OrgModel::where('id',$this->orgId)->value('permission');
        $permissionList=PermissionModel::where('id' ,'in' ,$permission)->select();
        $routes=PermissionModel::getTreeArr($permissionList);
        $routes=PermissionModel::executeTreeArr($routes);
        self::returnMsg(200,'message',$routes);
    }
    /**
     * 角色列表
     */
    public function roleList()
    {
        $param = Request()->param();
        $where=[
            'org_id'=>$this->orgId,
        ];
        if(isset($param['username']) && $param['username']){
            $where['username']=array('like',$param['username']);
        }
        $roles= RoleModel::where($where)->paginate($param['pageSize'], false, ['query' => $param]);
        self::returnMsg(200,'message',$roles);
    }


    /**
     * 角色新增
     */
    public function roleAdd(){
        $param = Request()->param();
        $insert=[
            'role_name' =>$param['role_name'],
            'router' =>implode(",", $param['permission']),
            'permission' =>implode(",", $param['permission']),
            'org_id'=>$this->orgId,
        ];
        $RoleModel=new RoleModel;
        $RoleModel->save($insert);
        self::returnMsg(200,'Success');
    }

    /**
     * 角色详情
     */
    public function roleDetail(){
        $param = Request()->param();
        $where=[
            'id' =>$param['id'],
        ];
        $detail=RoleModel::where($where)->find();
        $detail['permission']=explode(",", $detail['permission']);
        self::returnMsg(200,'Success',$detail);
    }

    /**
     * 角色编辑
     */
    public function roleEdit(){
        $param = Request()->param();

        $where=[
            'id' =>$param['id'],
            'org_id'=>$this->orgId,
        ];
        $update=[

            'role_name' =>$param['role_name'],
            'router' =>implode(",", $param['permission']),
            'permission' =>implode(",", $param['permission']),

        ];
        RoleModel::update($update,$where);
        self::returnMsg(200,'Success');
    }
    /**
     * 角色删除
     */
    public function roleDelete(){
        $param = Request()->param();

        $where=[
            'id' =>$param['id'],
            'org_id'=>$this->orgId,
        ];

        RoleModel::where($where)->delete();
        self::returnMsg(200,'Success');
    }


}