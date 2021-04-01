<?php
namespace app\admin\controller;

use app\admin\common\BaseOauthController;

use app\admin\model\Admin as AdminModel;

class Index extends BaseOauthController
{
    public function index()
    {

       $users= AdminModel::select();
        self::returnMsg(200,'message',$users);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
