<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019-06-28
 * Time: 17:42
 */

namespace app\admin\model;


use think\Model;


class BaseModel extends Model
{
    protected $hidden=['delete_time'];
    // 软删除，设置后在查询时要特别注意whereOr
    // 使用whereOr会将设置了软删除的记录也查询出来
    // 可以对比下SQL语句，看看whereOr的SQL




}