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
use app\common\StatusEnum;

use think\Exception;
use think\facade\Db;

class Permission extends BaseOauthController
{

    /**菜单权限列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permissionList()
    {

        if ($this->roles == StatusEnum::adminRole) {//机构总管理员
            $permission = OrgModel::where(['id' => $this->orgId])->value('permission');

        } else {
            $roles = explode(",", $this->roles);
            $permission = RoleModel::where('id', array('in', $roles))->select('permission');
        };
        $routes = PermissionModel::where('id', 'in', $permission)->order('display_order')->select();
        $routes=PermissionModel::getTreeArr($routes);
        self::returnMsg(200, 'message', $routes);
    }

    /**菜单权限新增
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permissionAdd()
    {
        $param = Request()->param();
        $where=[
            'name' => $param['name'],
        ];
        $exist=PermissionModel::where($where)->select();
        if(count($exist)){
            throw new Exception(
                 "菜单权限已存在",500
            );
        }
        Db::startTrans();
        try {

            $insert = [
                'title' => $param['title'],
                'name' => $param['name'],
                'parent_id' => $param['parent_id'],
                'path' => $param['path'],
                'component' => $param['component'],
                'redirect' => $param['redirect'],
                'hidden' => $param['hidden'],
                'icon' => $param['icon'],
                'display_order' => $param['display_order'],

            ];
            $PermissionModel = new PermissionModel;
            $PermissionModel->save($insert);
            //机构添加权限
            $orgList = OrgModel::where('parent_id',StatusEnum::orgParentId)->field('id,permission')->select();
            $update = [];
            foreach ($orgList as $k => $v) {
                $update[] = [
                    'id' => $v['id'],
                    'permission' => $v['permission'] . "," . $PermissionModel['id']
                ];
            }
            $OrgModel = new OrgModel;
            $OrgModel->saveAll($update);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            throw new Exception($msg);
        }
        self::returnMsg(200, 'Success');
    }

    /**菜单权限明细
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permissionDetail()
    {
        $param = Request()->param();
        $menu = PermissionModel::where('id', 'in', $param['id'])->find();
        self::returnMsg(200, 'Success',$menu);
    }
    /**菜单权限编辑
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permissionEdit()
    {
        $param = Request()->param();
        Db::startTrans();
        try {

            $where=[
                'id'=>$param['id'],
            ];
            $update = [
                'title' => $param['title'],
                'name' => $param['name'],
                'parent_id' => $param['parent_id'],
                'path' => $param['path'],
                'component' => $param['component'],
                'redirect' => isset($param['redirect'])?$param['redirect']:'',
                'hidden' => intval($param['hidden']),
                'icon' => $param['icon'],
                'display_order' => $param['display_order'],
            ];
            PermissionModel::update($update,$where);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            throw new Exception($msg);
        }
        self::returnMsg(200, 'Success');
    }
    /**
     * 删除
     */
    public function permissionDelete(){
        $param = Request()->param();
        Db::startTrans();
        try {

            $where=[
                'parent_id'=>$param['id'],
            ];
            $exist=PermissionModel::where($where)->select();
            if(count($exist)){
                throw new Exception(
                    "菜单权限有子菜单",500
                );
            }
            $where=[
                'id'=>$param['id'],
            ];
            PermissionModel::where($where)->delete();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            throw new Exception($msg);
        }
        self::returnMsg(200, 'Success');
    }
    /**上级菜单权限列表树节点
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function permissionRoutes()
    {
        $where = [
            'admin.token' => $this->token,
            'admin.status' => StatusEnum::orgOn,
            'org.status' => StatusEnum::orgOn
        ];

        $users = AdminModel::alias('admin')->
        join('org', 'org.id=admin.org_id')->where($where)->
        field('admin.expire_time,admin.id,admin.username,admin.token,admin.org_id,admin.roles,org.status,org.permission')->
        find();

        if ($users['roles'] == StatusEnum::adminRole) {
            $permission = $users['permission'];
        } else {
            $roles = explode(",", $users['roles']);
            $permission = RoleModel::where('id', array('in', $roles))->select('permission');
        };
        $route = PermissionModel::where('id', 'in', $permission)->order('display_order')->select();
        $route = PermissionModel::getPermissionTree($route);
        self::returnMsg(200, 'message', $route);
    }


}