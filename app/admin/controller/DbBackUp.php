<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/25
 * Time: 9:47
 */

namespace app\admin\controller;


use app\admin\common\BaseOauthController;
use tp5er\Backup;

class DbBackUp extends BaseOauthController
{

    public function dbBackUpList(){
        $config=array(
            'path'     => './DbBackUp/',//数据库备份路径
            'part'     => 20971520,//数据库备份卷大小
            'compress' => 0,//数据库备份文件是否启用压缩 0不压缩 1 压缩
            'level'    => 9 //数据库备份文件压缩级别 1普通 4 一般  9最高
        );
        $db= new Backup($config);
        self::returnMsg(200, 'message', $db->fileList());

    }

    /**
     * 备份
     */
    public function backUpDb(){
        $config=array(
            'path'     => './DbBackUp/',//数据库备份路径
            'part'     => 20971520,//数据库备份卷大小
            'compress' => 0,//数据库备份文件是否启用压缩 0不压缩 1 压缩
            'level'    => 9 //数据库备份文件压缩级别 1普通 4 一般  9最高
        );
        $db= new Backup($config);
        $tables=$db->dataList();//获取数据库所有表的信息
        foreach($tables as $k=>$v){
            $db->backup($v['name'],0);//循环所有表备份表和数据
        }
        $file=$db->getFile();//获取所备份文件的文件名
        exit(json_encode(['status'=>1,'msg'=>'备份成功']));
    }

}