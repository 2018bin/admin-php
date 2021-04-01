<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019-06-30
 * Time: 14:33
 */

namespace app\admin\model;


/**
 * Class Admin
 * @package app\admin\model
 */
class Permission extends BaseModel
{

    /**
     * 递归实现无限极分类列表
     * 菜单资源新增编辑选择上级路由的列表
     * @param  arr $data data
     * @param  int $parent_id pid
     * @param  int $level level
     * @return arr result
     */
    public static function getPermissionTree($data, $parent_id = 0, $level = 0, $parent_path = 'parent_path')
    {
        static $tree = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['parent_path'] = $parent_path . "_" . $value['id'];//记录分类路径
                $value['title'] = str_repeat('——', $level * 2) . $value['title'];
                $tree[] = $value;
                unset($data['key']);
                self::getPermissionTree($data, $value['id'], $level+1, $value['parent_path']);
            }
        }
        return $tree;
    }

    /**
     * 路由树节点格式化成路由格式
     * @param $data
     * @return array
     */
    public static function routerMapFormate($data){
        $res = array();
        foreach ($data as $key => $value) {
            $meta['title']=$value['title'];
            $meta['icon']=$value['icon'];
            $arr = [
                'path' => $value['path'],
                'fullPath' => $value['path'],
                'component' => $value['component'],
                'name' => $value['name'],
                'redirect' => $value['redirect'],
                'hidden' =>(bool)$value['hidden'] ,
                'meta' => $meta,
            ];
            if (!empty($value['children'])) {
                $arr['children'] = self::routerMapFormate($value['children']);
            }
            $res[$key] = $arr;
        }
        return $res;

    }
    /**
     * 获取无限极分类数组（直接是树形结构的数组） 直接循环输出时用 这里暂时无用,
     * @param $data
     * @return array
     */
    public static function getTreeArr($data)
    {
        //构造数据
        $items = array();
        //以分类的id为索引
        foreach ($data as $key => $value) {
            $items[$value['id']] = $value;
        }

        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach ($items as $key => $value) {
            if ($value['parent_id'] !== 0) {//不是顶级分类
                //把当前循环的value放入父节点下面
                $children=$items[$value['parent_id']]['children'];
                //间接修改重载元素无效
                if(is_array($children)){
                    $children[] = &$items[$key];
                }else{
                    $children=[];
                    $children[] = &$items[$key];
                }
                $items[$value['parent_id']]['children']=$children;
                //引用传值  当items更改时，tree里面的items也会更改
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
    /**
     * 递归返回可使用格式的权限节点
     * @param $data
     * @return array
     */
    public static function executeTreeArr($data)
    {
        $res = array();
        foreach ($data as $key => $value) {
            $arr = [
                'id' => $value['id'],
                'title' => $value['title'],
            ];
            if (!empty($value['children'])) {
                $arr['children'] = self::executeTreeArr($value['children']);
            }
            $res[$key] = $arr;
        }
        return $res;
    }




}