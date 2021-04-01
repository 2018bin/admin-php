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
use app\admin\model\Admin as AdminModel;
use app\admin\validate\UserValidate;
use app\common\StatusEnum;
use think\exception\ValidateException;

class Admin extends BaseOauthController
{

    /**
     * 用户列表
     */
    public function adminList(){
        $param = Request()->param();
        $where=[
            'org_id'=>$this->orgId,
        ];
        if(isset($param['username']) && $param['username']){
            $where['username']=array('like',$param['username']);
        }

        $admin=AdminModel::where($where)->paginate($param['pageSize'], false, ['query' => $param]);

        self::returnMsg(200,'message',$admin);
    }
    /**
     * 所有角色
     */
    public function getAllRoles(){

        $where=[
            'org_id'=>$this->orgId,
        ];
        $roles= RoleModel::where($where)->select();
        self::returnMsg(200,'message',$roles);
    }
    /**
     * 用户新增
     */
    public function adminAdd(){
        $param = Request()->param();
        $result = validate(UserValidate::class)->scene('add')->check([
            'username'  =>$param['username'],
            'password' => $param['password'],
            'roles' => $param['roles'],
            'status' => $param['status'],
        ]);
        if (true !== $result) {
            // 验证失败 输出错误信息
            throw new ValidateException([
                'msg' => $result
            ]);
        }
        $insert=[
            'roles' =>implode(",", $param['roles']),
            'username' =>$param['username'],
            'password' =>md5($param['password']),
            'status' =>$param['status'],
            'org_id'=>$this->orgId,
        ];
        $AdminModel=new AdminModel;
        $AdminModel->save($insert);
        self::returnMsg(200,'Success');
    }


    /**
     * 用户编辑
     */
    public function adminEdit(){
        $param = Request()->param();
        $result = validate(UserValidate::class)->scene('edit')->check([
            'id'  =>$param['id'],
            'username'  =>$param['username'],
            'roles' => $param['roles'],
            'status' => $param['status'],
        ]);
        if (!$result) {
            // 验证失败 输出错误信息
            throw new ValidateException([
                'msg' => $result
            ]);
        }
        $where=[
            'id' =>$param['id'],
            'org_id'=>$this->orgId,
        ];
        $update=[
            'roles' =>implode(",", $param['roles']),
            'username' =>$param['username'],
            'status' =>$param['status'],
        ];
        if(isset($param['password']) && $param['password']){
            $update['password']=md5($param['password']);
        }
        AdminModel::update($update,$where);
        self::returnMsg(200,'Success');
    }

    /**
     * 用户删除
     */
    public function adminDelete(){
        $param = Request()->param();
        $result = validate(UserValidate::class)->scene('delete')->check([
            'id'  =>$param['id'],
        ]);
        if (!$result) {
            // 验证失败 输出错误信息
            throw new ValidateException([
                'msg' => $result
            ]);
        }
        $where=[
            'id' =>$param['id'],
            'org_id'=>$this->orgId,
        ];

        AdminModel::where($where)->delete();
        self::returnMsg(200,'Success');
    }
}