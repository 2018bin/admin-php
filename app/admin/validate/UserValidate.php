<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/15
 * Time: 14:30
 */

namespace app\admin\validate;


use think\Validate;

class UserValidate extends Validate
{

    protected $rule =   [
        'id'  => 'require',
        'username'  => 'require|max:100',
        'password'   => 'require|max:200',
        'roles'   => 'require',
        'status'   => 'require',
    ];

    protected $message  =   [
        'id.require' => '账号id必须',
        'username.require' => '账号名称必须',
        'username.max'     => '账号名称最多不能超过100个字符',
        'password.number'   => '密码必须',
        'password.between'  => '密码最多不能超过100个字符-120之间',
        'roles.require' => '角色必须',
        'status.require' => '状态必须',
    ];
    protected $scene = [
        'login'  =>  ['username','password'],
        'add'  =>  ['username','password','roles','status'],
        'edit'  =>  ['id','username','roles','status'],
        'delete'  =>  ['id'],
    ];
    // 自定义验证规则
    protected function checkName($value, $rule, $data=[])
    {
        return $rule == $value ? true : '名称错误';
    }
}