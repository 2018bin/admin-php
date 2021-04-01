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

class Org extends BaseOauthController
{

    /**
     * 机构列表
     */
    public function orgList()
    {
        $param = Request()->param();
        $where = [
            'parent_id' => $this->orgId,
        ];
        if (isset($param['org_name']) && $param['org_name']) {
            $where['org_name'] = array('like', $param['org_name']);
        }

        $org = OrgModel::with(['org'])->where($where)->paginate($param['pageSize'], false, ['query' => $param]);
        foreach ($org as $v){
            $v['parent_org_name']=$v['org']['org_name'];
        }
        self::returnMsg(200, 'message', $org);
    }

    /**
     * 获取路由权限
     */
    public function getRoutes()
    {
        if ($this->parent_id == StatusEnum::orgParentId) {
            $permission = OrgModel::where('id', $this->orgId)->value('permission');
        } else {
            $permission = OrgModel::where('id', $this->parent_id)->value('permission');
        }

        $permissionList = PermissionModel::where('id', 'in', $permission)->select();
        $routes = PermissionModel::getTreeArr($permissionList);
        $routes = PermissionModel::executeTreeArr($routes);
        self::returnMsg(200, 'message', $routes);
    }


    /**
     * 机构新增
     */
    public function orgAdd()
    {
        $param = Request()->param();
        Db::startTrans();
        try {
            $insert = [
                'permission' => implode(",", $param['permission']),
                'org_name' => $param['org_name'],
                'status' => $param['status'],
                'parent_id' => $this->orgId,
            ];
            $OrgModel = new OrgModel;
            $OrgModel->save($insert);
            $where=[
                'username' => $param['username'],
            ];
            $isexist=AdminModel::where($where)->find();
            if(count($isexist)){
                throw new Exception( "账号名称已存在");
            }
            $adminInsert = [
                'roles' => StatusEnum::adminRole,
                'username' => $param['username'],
                'password' => md5($param['password']),
                'status' => $param['adminStatus'],
                'org_id' => $OrgModel->id,
            ];
            $AdminModel = new AdminModel;
            $AdminModel->save($adminInsert);


            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            throw new Exception( $msg);
        }
        self::returnMsg(200, 'Success');
    }
    /**
     * 机构删除
     */
    public function orgDelete(){
        $param = Request()->param();
        Db::startTrans();
        try {

            $orgId=OrgModel::childrenOrgDelete($param['id']);
            array_push($orgId,$param['id']) ;
            $orgId = array_unique($orgId);
            OrgModel::where('id','in',$orgId)->delete();
            AdminModel::where('org_id','in',$orgId)->delete();
            RoleModel::where('org_id','in',$orgId)->delete();


            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            throw new Exception( $msg);
        }
        self::returnMsg(200, 'Success');

    }

    /**
     * 机构详情
     */
    public function orgDetail()
    {
        $param = Request()->param();


        $where = [
            'id' => $param['id'],
        ];
        $orgDetail = OrgModel::with(['admin'])->where($where)->find();


        self::returnMsg(200, 'Success', $orgDetail);
    }

    /**
     * 机构编辑
     */
    public function orgEdit()
    {
        $param = Request()->param();
        Db::startTrans();
        try {

            //修改机构
            $where=[
                'id' => $param['id'],
            ];
            $update = [
                'permission' => implode(",", $param['permission']),
                'org_name' => $param['org_name'],
                'status' => $param['status'],
            ];
            $OrgDetail=OrgModel::where($where)->find();
            $oldPermission=explode(',',$OrgDetail['permission']);
            $OrgDetail->save($update);
            //修改总管理员
            $where = [
                'org_id' => $param['id'],
                'roles' => StatusEnum::adminRole,
            ];
            $update = [
                'username' => $param['username'],
                'status' => $param['adminStatus'],
            ];
            if(isset($param['password']) && $param['password']){
                $update['password']=md5($param['password']);
            }
            AdminModel::update($update, $where);
            //修改子机构的权限
            $reducediff =array_diff($oldPermission,$param['permission']);//对比旧权限所减少的权限
            $adddiff =array_diff($param['permission'],$oldPermission);//对比旧权限所新增的权限

            $orgList = OrgModel::where('parent_id', $param['id'])->select();
            $save=[];
            foreach ($orgList AS $k =>$v){
                //合并去重
                $permission=array_diff(explode(',',$v['permission']),$reducediff);
                $permission=array_unique(array_merge($permission, $adddiff));
                //删除减少的权限
                $save[]=[
                    'id'=>$v['id'],
                    'permission'=> implode(",",$permission ),
                ];
            }
            $OrgModel = new OrgModel;
            $OrgModel->saveAll($save);


            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            throw new Exception( $msg);
        }
        self::returnMsg(200, 'Success');
    }


}