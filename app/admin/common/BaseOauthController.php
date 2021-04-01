<?php

/**
 * API 用户权限访问基类
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:11
 */

namespace app\admin\common;


use app\admin\controller\Token;
use app\admin\model\Org;
use app\BaseController;

use app\common\StatusEnum;
use app\Send;
use app\admin\model\Admin as AdminModel;
use qeq66\think\Jump;


class BaseOauthController extends BaseController
{
    use Send;
    use Jump;

    public $token = '';
    protected $orgId = '';
    protected $parent_id = 0;//默认最小层级
    protected $roles = '';//账号角色


    protected function initialize()
    {
        //构造方法 设置允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,  Admin-Token,X-OrgId");
        header("Access-Control-Expose-Headers: Token,Code");
        header('Access-Control-Max-Age: 3600');
        $this->checkToken();
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            exit;
        }
        //构造方法 设置允许跨域请求


//        parent::_initialize();
    }




    /**
     * 每次请求验证token
     */
    protected function checkToken()
    {

        $headers = getallheaders();


        if (!isset($headers['admin-token'])) {
            header('Code:50008');//token不存在 登录
            exit;
        } else {

            //验证token
            $where=[
                'admin.token'=> substr($headers['admin-token'], 1),
                'admin.status'=>StatusEnum::orgOn,
                'org.status'=>StatusEnum::orgOn
            ];

            $user = AdminModel::alias('admin')
                ->join('org', 'org.id=admin.org_id')
                ->where($where)
                ->field('admin.expire_time,admin.id,admin.token,admin.org_id,admin.roles,org.status,org.parent_id')
                ->find();

            if (!$user) {
                header('Code:50008');//token不存在 登录
                exit;
            } else if (time() - $user['expire_time'] > 10) {
                //token过期 刷新token 返回新token值
                $token = Token::refreshUserToken($user['id']);
                if (!$token)
                    header('Code:50014');//刷新token失败返回token过期状态码
                header('Token:' . $token);
            } else if ($user['status'] == StatusEnum::orgOff) {
                header('Code:50016');//园区禁用，直接结束
                exit;
            }

            $this->token = isset($token) ? $token : $user['token'];
            $this->orgId = $user['org_id'];
            $this->parent_id = $user['parent_id'];
            $this->roles = $user['roles'];


//            session(OrgEnum::orgLevel, $this->org_lev);
//            session(OrgEnum::orgId, $this->orgId);
        }
    }


}