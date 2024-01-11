/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : 127.0.0.1:3306
 Source Schema         : 1043webman_huicmf_new

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 11/01/2024 15:05:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cmf_admin
-- ----------------------------
DROP TABLE IF EXISTS `cmf_admin`;
CREATE TABLE `cmf_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '加密盐',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机',
  `login_time` int(10) NOT NULL DEFAULT 0 COMMENT '登录时间',
  `login_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '上次登录ip',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '禁用',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_admin
-- ----------------------------
INSERT INTO `cmf_admin` VALUES (1, 'admin', '超级管理员', 'f878b6541920319cb3bb39b03dfcfc0d', '5kmb1G', '/storage/pic/20240111/58836ecd22a4286d66f8f4b427826a89.gif', 'admin@admin.com', '', 1704437120, '127.0.0.1', 1, 1703663893, 1704437120);

-- ----------------------------
-- Table structure for cmf_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `cmf_admin_role`;
CREATE TABLE `cmf_admin_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色id',
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_admin_id`(`role_id`, `admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_admin_role
-- ----------------------------
INSERT INTO `cmf_admin_role` VALUES (1, 1, 1);
INSERT INTO `cmf_admin_role` VALUES (2, 2, 2);

-- ----------------------------
-- Table structure for cmf_config
-- ----------------------------
DROP TABLE IF EXISTS `cmf_config`;
CREATE TABLE `cmf_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置类型',
  `title` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '配置值',
  `fieldtype` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段类型',
  `setting` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '字段设置',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `tips` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmf_config
-- ----------------------------
INSERT INTO `cmf_config` VALUES (1, 'site_name', 1, '站点名称', 'HuiCMF-webman后台权限管理系统V2', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (2, 'site_url', 1, '站点跟网址', 'http://127.0.0.1:8787', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (3, 'admin_log', 3, '启用后台管理操作日志', '0', 'radio', '', 1, '');
INSERT INTO `cmf_config` VALUES (4, 'site_keyword', 1, '站点关键字', 'huicmf,webman', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (5, 'site_copyright', 1, '网站版权信息', 'Powered By HuiCMF-V2后台系统 © 2019-2023 小灰灰工作室', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (6, 'site_beian', 1, '站点备案号', '豫ICP备666666号', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (7, 'site_description', 1, '站点描述', '', 'text', '', 1, '');
INSERT INTO `cmf_config` VALUES (8, 'site_code', 1, '统计代码', '', 'text', '', 1, '');
INSERT INTO `cmf_config` VALUES (9, 'admin_prohibit_ip', 2, '禁止访问网站的IP', '', 'text', '', 1, '');
INSERT INTO `cmf_config` VALUES (10, 'site_editor', 2, '文本编辑器', 'uEditorPlus', 'string', ' ', 1, '');
INSERT INTO `cmf_config` VALUES (11, 'watermark_enable', 2, '是否开启图片水印', '0', 'radio', '', 1, '');
INSERT INTO `cmf_config` VALUES (12, 'watermark_name', 2, '水印图片名称', 'mark.png', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (13, 'watermark_position', 2, '水印的位置', '9', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (14, 'watermark_touming', 2, '水印透明度', '74', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (15, 'upload_types_image', 2, '允许上传图片类型', 'jpg,jpeg,png,gif,bmp', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (16, 'upload_mode', 2, '图片上传方式', 'local', 'string', '', 1, '');
INSERT INTO `cmf_config` VALUES (17, 'pic_more_nums', 2, '多图上传图片数量限制', '0', 'string', NULL, 1, '');
INSERT INTO `cmf_config` VALUES (18, 'upload_types_file', 2, '允许上传附件类型', 'zip,pdf,doc,txt,json', 'string', ' ', 1, '');
INSERT INTO `cmf_config` VALUES (19, 'ueditor_icon', 4, 'ueditor图标显示', 'fullscreen,source,undo,redo,bold,italic,underline,fontborder,strikethrough,superscript,subscript,removeformat,formatmatch,autotypeset,blockquote,pasteplain,forecolor,backcolor,insertorderedlist,insertunorderedlist,selectall,cleardoc,rowspacingtop,rowspacingbottom,lineheight,customstyle,paragraph,fontfamily,fontsize,directionalityltr,directionalityrtl,indent,justifyleft,justifycenter,justifyright,justifyjustify,touppercase,tolowercase,link,unlink,anchor,imagenone,imageleft,imageright,imagecenter,simpleupload,insertimage,emotion,scrawl,attachment,insertcode,pagebreak,template,background,horizontal,date,time,spechars,wordimage,inserttable,deletetable,insertparagraphbeforetable,insertrow,deleterow,insertcol,deletecol,mergecells,mergeright,mergedown,splittocells,splittorows,splittocols,preview,help', 'text', NULL, 1, '');

-- ----------------------------
-- Table structure for cmf_dict
-- ----------------------------
DROP TABLE IF EXISTS `cmf_dict`;
CREATE TABLE `cmf_dict`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典名',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '字典值',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典管理' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_dict
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_log_login
-- ----------------------------
DROP TABLE IF EXISTS `cmf_log_login`;
CREATE TABLE `cmf_log_login`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '用户id',
  `admin_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `ip_address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `country` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '国家',
  `province` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '省',
  `city` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '市',
  `isp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '网络：【电信、联通】',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '登录状态：1=成功；0=失败',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `city`(`city`) USING BTREE,
  INDEX `area`(`province`) USING BTREE,
  INDEX `country`(`country`) USING BTREE,
  INDEX `ip_address`(`ip_address`) USING BTREE,
  INDEX `user`(`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台登录记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cmf_log_login
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_log_system
-- ----------------------------
DROP TABLE IF EXISTS `cmf_log_system`;
CREATE TABLE `cmf_log_system`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '管理员ID',
  `url` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作页面',
  `method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '请求方法',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '日志标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台操作日志表' ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of cmf_log_system
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_role
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role`;
CREATE TABLE `cmf_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级',
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色组',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '权限',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '状态',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_role
-- ----------------------------
INSERT INTO `cmf_role` VALUES (1, 0, '超级管理员', '*', 1, 1703668109, 1703668109);
INSERT INTO `cmf_role` VALUES (2, 1, '管理员', '1,2,6,7,3,9,10,11,4,13,14,15', 1, 1703668109, 1704879636);

-- ----------------------------
-- Table structure for cmf_rule
-- ----------------------------
DROP TABLE IF EXISTS `cmf_rule`;
CREATE TABLE `cmf_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标识',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级菜单',
  `href` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'url',
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '类型',
  `weight` int(11) NOT NULL DEFAULT 100 COMMENT '排序',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限规则' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_rule
-- ----------------------------
INSERT INTO `cmf_rule` VALUES (1, '权限管理', 'layui-icon-vercode', 'auth', 0, '', 0, 100, 1703668276, 1704249407);
INSERT INTO `cmf_rule` VALUES (2, '账户管理', 'layui-icon-username', 'plugin\\admin\\app\\controller\\AdminController', 1, '/app/admin/admin/index', 1, 100, 1703668276, 1704244813);
INSERT INTO `cmf_rule` VALUES (3, '角色管理', 'layui-icon-user', 'plugin\\admin\\app\\controller\\RoleController', 1, '/app/admin/role/index', 1, 100, 1703668276, 1704159765);
INSERT INTO `cmf_rule` VALUES (4, '菜单管理', 'layui-icon-menu-fill', 'plugin\\admin\\app\\controller\\RuleController', 1, '/app/admin/rule/index', 1, 100, 1703668276, 1704159790);
INSERT INTO `cmf_rule` VALUES (6, '添加', '', 'plugin\\admin\\app\\controller\\AdminController@add', 2, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (7, '编辑', '', 'plugin\\admin\\app\\controller\\AdminController@edit', 2, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (8, '删除', '', 'plugin\\admin\\app\\controller\\AdminController@delete', 2, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (9, '查询', '', 'plugin\\admin\\app\\controller\\RoleController@select', 3, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (10, '添加', '', 'plugin\\admin\\app\\controller\\RoleController@add', 3, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (11, '编辑', '', 'plugin\\admin\\app\\controller\\RoleController@edit', 3, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (12, '删除', '', 'plugin\\admin\\app\\controller\\RoleController@delete', 3, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (13, '查询', '', 'plugin\\admin\\app\\controller\\RuleController@select', 4, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (14, '添加', '', 'plugin\\admin\\app\\controller\\RuleController@add', 4, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (15, '编辑', '', 'plugin\\admin\\app\\controller\\RuleController@edit', 4, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (16, '删除', '', 'plugin\\admin\\app\\controller\\RuleController@delete', 4, '', 2, 100, 1704189939, 1704189939);
INSERT INTO `cmf_rule` VALUES (17, '通用设置', 'layui-icon-set', 'config', 0, '', 0, 100, 1704856568, 1704879625);
INSERT INTO `cmf_rule` VALUES (18, '系统设置', 'layui-icon-set', 'plugin\\admin\\app\\controller\\ConfigController', 17, '/app/admin/config/index', 1, 100, 1704856634, 1704856634);
INSERT INTO `cmf_rule` VALUES (19, '查询', '', 'plugin\\admin\\app\\controller\\ConfigController@select', 18, '', 2, 100, 1704856974, 1704856974);
INSERT INTO `cmf_rule` VALUES (20, '添加', '', 'plugin\\admin\\app\\controller\\ConfigController@add', 18, '', 2, 100, 1704856974, 1704856974);
INSERT INTO `cmf_rule` VALUES (21, '编辑', '', 'plugin\\admin\\app\\controller\\ConfigController@edit', 18, '', 2, 100, 1704856974, 1704856974);
INSERT INTO `cmf_rule` VALUES (22, '删除', '', 'plugin\\admin\\app\\controller\\ConfigController@delete', 18, '', 2, 100, 1704856974, 1704856974);
INSERT INTO `cmf_rule` VALUES (23, '字典管理', 'layui-icon-diamond', 'plugin\\admin\\app\\controller\\DictController', 17, '/app/admin/dict/index', 1, 100, 1704936969, 1704956368);
INSERT INTO `cmf_rule` VALUES (24, '查询', '', 'plugin\\admin\\app\\controller\\DictController@select', 23, '', 2, 100, 1704937017, 1704937017);
INSERT INTO `cmf_rule` VALUES (25, '添加', '', 'plugin\\admin\\app\\controller\\DictController@add', 23, '', 2, 100, 1704937017, 1704937017);
INSERT INTO `cmf_rule` VALUES (26, '编辑', '', 'plugin\\admin\\app\\controller\\DictController@edit', 23, '', 2, 100, 1704937017, 1704937017);
INSERT INTO `cmf_rule` VALUES (27, '删除', '', 'plugin\\admin\\app\\controller\\DictController@delete', 23, '', 2, 100, 1704937017, 1704937017);

-- ----------------------------
-- Table structure for cmf_upload_file
-- ----------------------------
DROP TABLE IF EXISTS `cmf_upload_file`;
CREATE TABLE `cmf_upload_file`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件id',
  `storage` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `group_id` int(11) NOT NULL DEFAULT 0 COMMENT '文件分组id',
  `file_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小(字节)',
  `image_width` int(11) NOT NULL DEFAULT 0 COMMENT '图片宽度',
  `image_height` int(11) NOT NULL DEFAULT 0 COMMENT '图片高度',
  `file_type` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `extension` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `is_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '软删除',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_upload_file
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_upload_group
-- ----------------------------
DROP TABLE IF EXISTS `cmf_upload_group`;
CREATE TABLE `cmf_upload_group`  (
  `group_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `group_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `parent_id` int(10) NOT NULL DEFAULT 0 COMMENT '父级id（保留字段）',
  `group_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类排序(数字越小越靠前)',
  `wxapp_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '小程序id',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`group_id`) USING BTREE,
  INDEX `type_index`(`group_type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件分组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cmf_upload_group
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
