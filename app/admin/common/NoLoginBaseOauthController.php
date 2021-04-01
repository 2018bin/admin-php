<?php

/**
 * API 用户权限访问基类
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:11
 */

namespace app\admin\common;


use app\BaseController;

use app\Send;



class NoLoginBaseOauthController extends BaseController
{
    use Send;


    public function _initialize()
    {
        //构造方法 设置允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,  X-Token,X-OrgId");
        header("Access-Control-Expose-Headers: Token,Code");
        header('Access-Control-Max-Age: 3600');
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            exit;
        }
        //构造方法 设置允许跨域请求

        parent::_initialize();
    }





}