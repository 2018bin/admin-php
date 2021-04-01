<?php

namespace app\admin\controller;


use app\admin\model\Admin as AdminModel;
use app\admin\validate\UserValidate;
use app\admin\model\Role as RoleModel;
use app\admin\model\Permission as PermissionModel;
use app\common\StatusEnum;
use app\Send;
use think\Exception;


class User
{
    use Send;

    /**用户登录
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException*/

    public function login()
    {

        $param = Request()->param();


        validate(UserValidate::class)->scene('login')->check([
            'username'  =>$param['username'],
            'password' => $param['password'],
        ]);
        $where=[
           'username' =>$param['username'],
            'password'=>md5($param['password']),
        ];
        $users = AdminModel::where($where)->order('id desc')->find();
        if(!$users){
            self::returnMsg(500, '账号或密码错误');

        }
        if($users['status']==StatusEnum::orgOff){
            self::returnMsg(500, '账号已禁用');

        }
        $token = Token::refreshUserToken($users['id']);
        $users->token  = $token;

        self::returnMsg(200, 'message', $users);
    }

    /**用户信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException*/
    public function getInfo(){
        $param = Request()->param();
        $where=[
            'admin.token' =>$param['token'],
            'admin.status'=>StatusEnum::orgOn,
            'org.status'=>StatusEnum::orgOn
        ];
        $users = AdminModel::alias('admin')->
            join('org','org.id=admin.org_id')->where($where)->
        field('admin.expire_time,admin.id,admin.username,admin.token,admin.org_id,admin.roles,org.status,org.parent_id,org.permission')->
        find();
        $users['roles']=explode(",",$users['roles']);
        self::returnMsg(200, 'message', $users);
    }
    /**用户路由权限
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException*/
    public function getPermission(){
        $param = Request()->param();
        $where=[
            'admin.token' =>$param['token'],
            'admin.status'=>StatusEnum::orgOn,
            'org.status'=>StatusEnum::orgOn
        ];

        $users = AdminModel::alias('admin')->
        join('org','org.id=admin.org_id')->where($where)->
        field('admin.expire_time,admin.id,admin.username,admin.token,admin.org_id,admin.roles,org.status,org.parent_id,org.permission')->
        find();

        if($users['roles']==StatusEnum::adminRole){
            $permission=$users['permission'];
        }else{
            $roles=explode(",",$users['roles']);
            $permission=RoleModel::where('id',array('in',$roles))->select('permission');
        };
        $route= PermissionModel::where('id','in',$permission)->order('display_order')->select();
        $route=PermissionModel::getTreeArr($route);
        $route=PermissionModel::routerMapFormate($route);
        self::returnMsg(200, 'message', $route);
    }
    public function logout(){
        $param = Request()->param();
        $where=[
            'token' =>$param['token'],
            'status'=>StatusEnum::orgOn,
        ];
        $users = AdminModel::where($where)->find();
        $users['token']="";
        self::returnMsg(200, 'message', $users);
    }


}
