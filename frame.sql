/*
 Navicat Premium Data Transfer

 Source Server         : shidai
 Source Server Type    : MySQL
 Source Server Version : 80026
 Source Host           : localhost:3306
 Source Schema         : feedback

 Target Server Type    : MySQL
 Target Server Version : 80026
 File Encoding         : 65001

 Date: 02/09/2022 10:58:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `project_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '部门名称',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '部门描述',
  `contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `sort` smallint NOT NULL DEFAULT '0' COMMENT '排序',
  `level` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `status` tinyint unsigned NOT NULL DEFAULT '80' COMMENT '状态 40冻结',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `department_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='部门表';

-- ----------------------------
-- Records of department
-- ----------------------------
BEGIN;
INSERT INTO `department` (`id`, `parent_id`, `project_id`, `name`, `description`, `contact`, `sort`, `level`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 0, 0, '平台中心', '平台中心管理部门描述', '88978888', 0, 1, 80, '2021-08-31 23:34:23', '2021-09-03 09:08:12', NULL);
INSERT INTO `department` (`id`, `parent_id`, `project_id`, `name`, `description`, `contact`, `sort`, `level`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES (2, 1, 0, '项目部', '项目部部门描述', '88978887', 0, 1, 80, '2021-08-31 23:39:48', '2021-09-03 09:08:34', NULL);
INSERT INTO `department` (`id`, `parent_id`, `project_id`, `name`, `description`, `contact`, `sort`, `level`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES (3, 1, 0, '设计部', '设计部部门描述', '88978886', 0, 1, 80, '2021-08-31 23:40:16', '2021-09-03 09:09:03', NULL);
COMMIT;

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `parent_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'PID',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单名称',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由名称',
  `path` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由地址',
  `component` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '组件地址',
  `redirect` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '重定向地址',
  `icon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `display_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转地址',
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web' COMMENT '守卫类型 如：web',
  `sort` smallint NOT NULL DEFAULT '0' COMMENT '排序，正序排序',
  `type` tinyint unsigned NOT NULL DEFAULT '10' COMMENT '类型 10菜单 20接口 30跳转',
  `status` tinyint unsigned NOT NULL DEFAULT '80' COMMENT '状态 40冻结',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_title_unique` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='资源权限表';

-- ----------------------------
-- Records of permission
-- ----------------------------
BEGIN;
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (1, 0, 0, '控制台', 'dashboard', '/dashboard', './welcome', '', 'dashboard', '', '', 'web', 1, 10, 80, '2021-08-29 23:27:05', '2021-08-29 23:27:05');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (2, 0, 0, '项目管理', 'project', '/project', './project', '', 'appstore', '', '', 'web', 2, 10, 80, '2021-08-29 23:35:53', '2021-08-29 23:35:53');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (3, 0, 0, '系统管理', 'system', '/system', '', '', 'setting', '', '', 'web', 199, 10, 80, '2021-08-29 23:38:50', '2021-12-01 11:47:18');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (4, 0, 3, '菜单管理', 'permission', '/system/permission', './system/permission', '', 'link', '', '', 'web', 1, 10, 80, '2021-08-29 23:41:16', '2021-08-29 23:41:16');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (5, 0, 3, '角色管理', 'role', '/system/role', './system/role', '', 'link', '', '', 'web', 2, 10, 80, '2021-08-29 23:42:05', '2021-08-29 23:42:05');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (6, 0, 3, '部门管理', 'department', '/system/department', './system/department', '', 'link', '', '', 'web', 3, 10, 80, '2021-08-29 23:42:45', '2021-08-29 23:42:45');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (7, 0, 3, '用户管理', 'user', '/system/user', './system/user', '', 'link', '', '', 'web', 4, 10, 80, '2021-08-29 23:43:19', '2021-08-29 23:43:19');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (8, 0, 3, '公告管理', 'notice', '/system/notice', './system/notice', '', 'link', '', '', 'web', 5, 10, 80, '2021-08-29 23:44:02', '2021-08-29 23:44:02');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (9, 0, 3, '操作管理', 'log', '/system/log', './system/log', '', 'link', '', '', 'web', 6, 10, 80, '2021-08-29 23:44:42', '2021-08-29 23:44:42');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (10, 0, 2, '项目列表', '/v1/console/project/index/list', '/v1/console/project/index/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 11:19:34', '2021-09-03 11:19:34');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (11, 0, 2, '项目添加', '/v1/console/project/index/created', '/v1/console/project/index/created', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 16:21:08', '2021-09-03 16:21:08');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (12, 0, 2, '项目更新', '/v1/console/project/index/updated', '/v1/console/project/index/updated', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 16:21:32', '2021-09-03 16:21:32');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (13, 0, 2, '项目冻结', '/v1/console/project/index/freeze', '/v1/console/project/index/freeze', '', '', '', '', '', 'web', 4, 20, 80, '2021-09-03 16:22:11', '2021-09-03 16:25:04');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (14, 0, 2, '项目解冻', '/v1/console/project/index/unfreeze', '/v1/console/project/index/unfreeze', '', '', '', '', '', 'web', 5, 20, 80, '2021-09-03 16:23:04', '2021-09-03 16:25:24');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (15, 0, 4, '菜单列表', '/v1/console/system/permission/list', '/v1/console/system/permission/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 16:26:51', '2021-09-03 16:26:51');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (16, 0, 4, '菜单添加', '/v1/console/system/permission/created', '/v1/console/system/permission/created', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 16:27:45', '2021-09-03 16:27:45');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (17, 0, 4, '菜单更新', '/v1/console/system/permission/updated', '/v1/console/system/permission/updated', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 16:28:21', '2021-09-03 16:28:21');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (18, 0, 4, '菜单删除', '/v1/console/system/permission/deleted', '/v1/console/system/permission/deleted', '', '', '', '', '', 'web', 4, 20, 80, '2021-09-03 16:28:44', '2021-09-03 16:28:44');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (19, 0, 4, '菜单信息', '/v1/console/system/permission/items', '/v1/console/system/permission/items', '', '', '', '', '', 'web', 5, 20, 80, '2021-09-03 16:29:05', '2021-09-03 16:29:05');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (20, 0, 5, '角色列表', '/v1/console/system/role/list', '/v1/console/system/role/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 16:30:27', '2021-09-03 16:30:27');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (21, 0, 5, '角色添加', '/v1/console/system/role/created', '/v1/console/system/role/created', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 16:31:05', '2021-09-03 16:31:05');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (22, 0, 5, '角色更新', '/v1/console/system/role/updated', '/v1/console/system/role/updated', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 16:31:22', '2021-09-03 16:31:22');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (23, 0, 5, '角色删除', '/v1/console/system/role/deleted', '/v1/console/system/role/deleted', '', '', '', '', '', 'web', 4, 20, 80, '2021-09-03 16:31:49', '2021-09-03 16:31:49');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (24, 0, 5, '角色绑定权限', '/v1/console/system/role/bind', '/v1/console/system/role/bind', '', '', '', '', '', 'web', 5, 20, 80, '2021-09-03 16:35:02', '2021-09-03 16:37:53');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (25, 0, 5, '角色清除权限', '/v1/console/system/role/clear', '/v1/console/system/role/clear', '', '', '', '', '', 'web', 6, 20, 80, '2021-09-03 16:37:42', '2021-09-03 16:37:42');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (26, 0, 5, '角色获取权限', '/v1/console/system/role/permission', '/v1/console/system/role/permission', '', '', '', '', '', 'web', 7, 20, 80, '2021-09-03 16:39:36', '2021-09-03 16:39:36');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (27, 0, 5, '角色获取项目', '/v1/console/system/role/project', '/v1/console/system/role/project', '', '', '', '', '', 'web', 8, 20, 80, '2021-09-03 16:40:22', '2021-09-03 16:40:22');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (28, 0, 6, '部门列表', '/v1/console/system/department/list', '/v1/console/system/department/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 16:41:30', '2021-09-03 16:41:30');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (29, 0, 6, '部门添加', '/v1/console/system/department/created', '/v1/console/system/department/created', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 16:41:57', '2021-09-03 16:41:57');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (30, 0, 6, '部门更新', '/v1/console/system/department/updated', '/v1/console/system/department/updated', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 16:42:17', '2021-09-03 16:42:17');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (31, 0, 6, '部门删除', '/v1/console/system/department/deleted', '/v1/console/system/department/deleted', '', '', '', '', '', 'web', 4, 20, 80, '2021-09-03 16:42:47', '2021-09-03 16:42:47');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (32, 0, 6, '部门获取项目', '/v1/console/system/department/project', '/v1/console/system/department/project', '', '', '', '', '', 'web', 5, 20, 80, '2021-09-03 16:43:07', '2021-09-03 16:43:07');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (33, 0, 7, '用户列表', '/v1/console/system/user/list', '/v1/console/system/user/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 16:49:35', '2021-09-03 16:49:35');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (34, 0, 7, '用户添加', '/v1/console/system/user/created', '/v1/console/system/user/created', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 16:50:15', '2021-09-03 16:50:15');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (35, 0, 7, '用户更新', '/v1/console/system/user/updated', '/v1/console/system/user/updated', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 16:50:41', '2021-09-03 16:50:41');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (36, 0, 7, '用户冻结', '/v1/console/system/user/freeze', '/v1/console/system/user/freeze', '', '', '', '', '', 'web', 4, 20, 80, '2021-09-03 16:52:40', '2021-09-03 16:52:40');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (37, 0, 7, '用户解冻', '/v1/console/system/user/unfreeze', '/v1/console/system/user/unfreeze', '', '', '', '', '', 'web', 5, 20, 80, '2021-09-03 16:53:11', '2021-09-03 16:53:11');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (38, 0, 7, '用户绑定权限', '/v1/console/system/user/bind', '/v1/console/system/user/bind', '', '', '', '', '', 'web', 6, 20, 80, '2021-09-03 16:54:03', '2021-09-03 16:54:03');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (39, 0, 7, '用户清除权限', '/v1/console/system/user/clear', '/v1/console/system/user/clear', '', '', '', '', '', 'web', 7, 20, 80, '2021-09-03 16:54:27', '2021-09-03 16:56:18');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (40, 0, 7, '用户获取权限', '/v1/console/system/user/permission', '/v1/console/system/user/permission', '', '', '', '', '', 'web', 8, 20, 80, '2021-09-03 16:56:47', '2021-09-03 16:57:31');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (41, 0, 7, '用户获取信息', '/v1/console/system/user/info', '/v1/console/system/user/info', '', '', '', '', '', 'web', 9, 20, 80, '2021-09-03 16:58:00', '2021-09-03 16:58:00');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (42, 0, 8, '公告列表', '/v1/console/system/notice/list', '/v1/console/system/notice/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 16:59:42', '2021-09-03 16:59:42');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (43, 0, 8, '公告添加', '/v1/console/system/notice/created', '/v1/console/system/notice/created', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 17:00:15', '2021-09-03 17:00:15');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (44, 0, 8, '公告删除', '/v1/console/system/notice/deleted', '/v1/console/system/notice/deleted', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 17:00:38', '2021-09-03 17:00:38');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (45, 0, 8, '公告撤回', '/v1/console/system/notice/withdraw', '/v1/console/system/notice/withdraw', '', '', '', '', '', 'web', 4, 20, 80, '2021-09-03 17:01:10', '2021-09-03 17:01:10');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (46, 0, 8, '公告获取部门', '/v1/console/system/notice/department', '/v1/console/system/notice/department', '', '', '', '', '', 'web', 5, 20, 80, '2021-09-03 17:02:08', '2021-09-03 17:02:08');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (47, 0, 8, '公告获取用户', '/v1/console/system/notice/user', '/v1/console/system/notice/user', '', '', '', '', '', 'web', 6, 20, 80, '2021-09-03 17:02:46', '2021-09-03 17:02:46');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (48, 0, 9, '操作列表', '/v1/console/system/log/list', '/v1/console/system/log/list', '', '', '', '', '', 'web', 1, 20, 80, '2021-09-03 17:03:43', '2021-09-03 17:03:43');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (49, 0, 9, '操作删除', '/v1/console/system/log/deleted', '/v1/console/system/log/deleted', '', '', '', '', '', 'web', 2, 20, 80, '2021-09-03 17:04:02', '2021-09-03 17:04:02');
INSERT INTO `permission` (`id`, `project_id`, `parent_id`, `title`, `name`, `path`, `component`, `redirect`, `icon`, `display_name`, `url`, `guard_name`, `sort`, `type`, `status`, `created_at`, `updated_at`) VALUES (50, 0, 9, '操作获取信息', '/v1/console/system/log/options', '/v1/console/system/log/options', '', '', '', '', '', 'web', 3, 20, 80, '2021-09-03 17:04:34', '2021-09-03 17:04:34');
COMMIT;

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目名称',
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目简介',
  `path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目域名地址',
  `remark` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint unsigned NOT NULL DEFAULT '80' COMMENT '状态 40冻结 44到期',
  `expire_at` timestamp NULL DEFAULT NULL COMMENT '到期时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='项目表';

-- ----------------------------
-- Records of project
-- ----------------------------
BEGIN;
INSERT INTO `project` (`id`, `name`, `description`, `path`, `remark`, `status`, `expire_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, '永久项目', '永久项目拥有永久使用权', 'https://app.xxx.com', '', 80, NULL, '2021-09-03 09:42:36', '2021-09-03 17:21:23', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
