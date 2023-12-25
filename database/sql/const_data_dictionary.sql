-- 前置数据 common.language
-- 数据字典 集中配置  支持无限级分类
const_data_dictionary

全局约束：is_sth 可选项 禁用0，（0、空 都表示全部），1表示true,2 表示 false

CREATE TABLE `const_data_dictionary` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID，自增',
  `tenant_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代码租户ID',
  `data_code` varchar(25) NOT NULL DEFAULT '' COMMENT '唯一编码',
  `parent_code` varchar(25) NOT NULL DEFAULT '' COMMENT '父级编码',
  `name_en` varchar(100) NOT NULL DEFAULT '' COMMENT '英文名称(多语言默认英文)',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '层级',
  `path` varchar(350) NOT NULL DEFAULT '' COMMENT '分类路径，用逗号隔开',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '状态：1、启用 2、禁用',
  `created_by` varchar(25) NOT NULL DEFAULT '' COMMENT '创建用户',
  `updated_by` varchar(25) NOT NULL DEFAULT '' COMMENT '最后修改',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tenant_id_data_code` (`tenant_id`,`data_code`) USING BTREE,
  UNIQUE KEY `parent_code_level_name` (`parent_code`,`level`,`name_en`) USING BTREE,
  KEY `idx_updated_at` (`updated_at`) USING BTREE,
  KEY `idx_parent_code` (`parent_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='常量字典';


CREATE TABLE `const_data_name_l18n` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID，自增',
  `tenant_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代码租户ID',
  `uniq_code` varchar(25) NOT NULL DEFAULT '' COMMENT '唯一编码',
  `data_code` varchar(25) NOT NULL DEFAULT '' COMMENT '数据字典编码',
  `language_code` varchar(40) NOT NULL DEFAULT '' COMMENT '语言编码',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称(en)',
  `created_by` varchar(25) NOT NULL DEFAULT '' COMMENT '创建用户',
  `updated_by` varchar(25) NOT NULL DEFAULT '' COMMENT '最后修改',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_code` (`uniq_code`) USING BTREE,
  UNIQUE KEY `tenant_id_data_code_language_code` (`tenant_id`,`data_code`,`language_code`) USING BTREE,
  KEY `idx_updated_at` (`updated_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='常量多语言';
