# 后台管理系统

## 前言
该项目为前后端分离项目的后端部分，前端端项目地址：[传送门](https://github.com/2018bin/vue3-admin)。

## 项目介绍
这是一个后台管理系统的后端，基于ThinkPHP6+MySql实现，主要包括用户管理，菜单资源管理、角色管理、机构管理。

## 项目演示
[项目在线演示地址](www.bin.organic)

## 组织结构
```
www  WEB部署目录（或者子目录）
├─app           应用目录
│  ├─admin           后台系统应用目录
│  │  ├─common.php      函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─config          配置目录
│  │  ├─route           路由目录
│  │  └─ ...            更多类库目录
│  │
│  ├─index           应用目录
│  │  ├─common.php      函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─config          配置目录
│  │  ├─route           路由目录
│  │  └─ ...            更多类库目录
│  │
│  ├─common         公共函数文件夹
│  ├─common.php         公共函数文件
│  └─event.php          事件定义文件
│
├─config                全局配置目录
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─console.php        控制台配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─filesystem.php     文件磁盘配置
│  ├─lang.php           多语言配置
│  ├─log.php            日志配置
│  ├─middleware.php     中间件配置
│  ├─route.php          URL和路由配置
│  ├─session.php        Session配置
│  ├─trace.php          Trace配置
│  └─view.php           视图配置
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                Composer类库目录
├─.example.env          环境变量示例文件
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
```
## 技术栈
### 后端
|  技术       | 说明     |    官网  | 
|  ---         |   ---  |     ---  |  
|   Mysql      |数据库	  | https://www.mysql.com/ |
|   ThinkPHP6  |框架	  | https://www.kancloud.cn/manual/thinkphp6_0/1037479  |
 
### 前端
|  技术       | 说明     |    官网  | 
|  ---         |   ---  |     ---  |  
|   Vue3      |前端框架	  | https://www.vue3js.cn/docs/zh/guide/introduction.html |
|   Vue-router |路由框架	  | https://router.vuejs.org/  |
|   Vuex       |全局状态管理框架	  | https://github.com/vuejs/vuex  |
|   Axios      |HTTP框架	  | http://www.axios-js.com/  |
|   Ant Design of Vue |	前端UI框架	  | https://2x.antdv.com/docs/vue/introduce-cn/  |
## 开发环境
|  工具   | 版本号 |   
|  ---    |   ---  |  
|   Mysql  |5.7	    | 
|   Nginx  |1.10	     | 
|   php	   |7.2     | 
|   node.js	|12.6.0	 | 
 
## 搭建步骤
- 配置PHP和MySql
-  ```   git clone    https://github.com/2018bin/admin-php.git  ```
- 配置apache
- 在/config/database.php配置数据库


