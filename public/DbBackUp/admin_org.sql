/*
 Navicat Premium Data Transfer

 Source Server         : 106.52.207.214org
 Source Server Type    : MySQL
 Source Server Version : 50648
 Source Host           : 106.52.207.214:3306
 Source Schema         : admin_org

 Target Server Type    : MySQL
 Target Server Version : 50648
 File Encoding         : 65001

 Date: 01/04/2021 11:21:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for a_admin
-- ----------------------------
DROP TABLE IF EXISTS `a_admin`;
CREATE TABLE `a_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登录验证',
  `expire_time` int(11) NULL DEFAULT NULL COMMENT '登陆时间',
  `roles` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色id',
  `org_id` int(11) NULL DEFAULT NULL COMMENT '机构id',
  `status` bit(1) NULL DEFAULT b'1' COMMENT '1启用，0禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of a_admin
-- ----------------------------
INSERT INTO `a_admin` VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '705cb3c5f61593e46a929462e05a5433', 1617268381, 'admin', 1, b'1');
INSERT INTO `a_admin` VALUES (2, 'admin2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'admin', 3, b'1');
INSERT INTO `a_admin` VALUES (3, '123', '202cb962ac59075b964b07152d234b70', NULL, NULL, '2,1', 1, b'1');

-- ----------------------------
-- Table structure for a_org
-- ----------------------------
DROP TABLE IF EXISTS `a_org`;
CREATE TABLE `a_org`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `org_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '机构名称',
  `parent_id` int(11) NULL DEFAULT 0 COMMENT '父机构id',
  `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限id',
  `status` bit(1) NULL DEFAULT b'1' COMMENT '1启用，0禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of a_org
-- ----------------------------
INSERT INTO `a_org` VALUES (1, '总部', 0, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20', b'1');
INSERT INTO `a_org` VALUES (2, '平台1', 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18', b'1');
INSERT INTO `a_org` VALUES (3, '平台2', 1, '17,18,1,2,3,4,5,6,7,8,9,10,11,12', b'1');

-- ----------------------------
-- Table structure for a_permission
-- ----------------------------
DROP TABLE IF EXISTS `a_permission`;
CREATE TABLE `a_permission`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单标题',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `parent_id` int(11) NULL DEFAULT NULL COMMENT '上级菜单id',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单路由',
  `component` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单路由名称',
  `redirect` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '重定向',
  `hidden` bit(1) NULL DEFAULT b'0' COMMENT '1隐藏，0显示',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单图标',
  `display_order` int(11) NULL DEFAULT 1 COMMENT '菜单排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of a_permission
-- ----------------------------
INSERT INTO `a_permission` VALUES (1, '菜单资源', 'permission', 20, '/setting/permission/permission', '/setting/permission/permission', '/setting/permission/permissionList', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (2, '菜单资源列表', 'permissionList', 1, '/setting/permission/permissionList', '/setting/permission/permissionList', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (3, '菜单资源新增', 'permissionAdd', 1, '/setting/permission/permissionAdd', '/setting/permission/permissionAdd', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (4, 'permissionEdit', 'permissionEdit', 1, '/setting/permission/permissionEdit', '/setting/permission/permissionEdit', NULL, b'1', 'table-2', 1);
INSERT INTO `a_permission` VALUES (5, '机构', 'org', 20, '/setting/org/org', '/setting/org/org', '/setting/org/orgList', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (6, '机构列表', 'orgList', 5, '/setting/org/orgList', '/setting/org/orgList', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (7, '机构新增', 'orgAdd', 5, '/setting/org/orgAdd', '/setting/org/orgAdd', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (8, '机构编辑', 'orgEdit', 5, '/setting/org/orgEdit', '/setting/org/orgEdit', '', b'1', 'table-2', 1);
INSERT INTO `a_permission` VALUES (9, '角色管理', 'role', 20, '/setting/role/role', '/setting/role/role', '/role/roleList', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (10, '角色新增', 'roleAdd', 9, '/setting/role/roleAdd', '/setting/role/roleAdd', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (11, '角色列表', 'roleList', 9, '/setting/role/roleList', '/setting/role/roleList', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (12, '角色编辑', 'roleEdit', 9, '/setting/role/roleEdit', '/setting/role/roleEdit', '', b'1', 'table-2', 1);
INSERT INTO `a_permission` VALUES (13, '用户管理', 'admin', 20, '/setting/admin/admin', '/setting/admin/admin', '/admin/adminList', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (14, '用户列表', 'adminList', 13, '/setting/admin/adminList', '/setting/admin/adminList', '', b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (15, '用户新增', 'adminAdd', 13, '/setting/admin/adminAdd', '/setting/admin/adminAdd', '', b'1', 'table-2', 1);
INSERT INTO `a_permission` VALUES (16, '用户编辑', 'adminEdit', 13, '/setting/admin/adminEdit', '/setting/admin/adminEdit', '', b'1', 'table-2', 1);
INSERT INTO `a_permission` VALUES (17, '首页', '/', 0, '/', 'Layout', '/index', b'0', 'table-2', 0);
INSERT INTO `a_permission` VALUES (18, '首页', 'index', 17, '/index', 'index/index', NULL, b'0', 'table-2', 1);
INSERT INTO `a_permission` VALUES (20, '系统设置', 'setting', 0, '/setting/setting', 'Layout', '', b'0', 'table-2', 0);

-- ----------------------------
-- Table structure for a_role
-- ----------------------------
DROP TABLE IF EXISTS `a_role`;
CREATE TABLE `a_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色名称',
  `router` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路由权限',
  `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单权限',
  `org_id` int(11) NULL DEFAULT NULL COMMENT '机构id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of a_role
-- ----------------------------
INSERT INTO `a_role` VALUES (1, '总管理员', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19', 1);
INSERT INTO `a_role` VALUES (2, '管理员', '17,18,1,2,3,4,5,6,7,8', '17,18,1,2,3,4,5,6,7,8', 1);

SET FOREIGN_KEY_CHECKS = 1;
