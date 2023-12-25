-- 前置依赖，系统内部不定义数据库字段可选值的常量
-- 常量通过表存储 DataDictionary

CREATE TABLE `rule_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租户ID',
  `type_code` varchar(18) NOT NULL COMMENT '规则编码（雪花算法生成）',
  `type_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `condition_arguments` json NOT NULL COMMENT '条件参数及可选项',
  `action_arguments` json NOT NULL COMMENT '行为参数及可选项',
  `created_by` varchar(100) NOT NULL DEFAULT '' COMMENT '创建用户',
  `updated_by` varchar(100) NOT NULL DEFAULT '' COMMENT '修改用户',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_code` (`type_code`),
  UNIQUE KEY `uniq_tenant_id_type_name` (`tenant_id`,`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='规则表';

CREATE TABLE `rule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租户ID',
  `rule_code` varchar(18) NOT NULL COMMENT '规则编码（雪花算法生成）',
  `rule_name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `type_code` varchar(18) unsigned NOT NULL COMMENT '规则类型',
  `level` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '优先级',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1.启用;2.禁用',
  `arguments` json NOT NULL COMMENT '规则参数',
  `created_by` varchar(100) NOT NULL DEFAULT '' COMMENT '创建用户',
  `updated_by` varchar(100) NOT NULL DEFAULT '' COMMENT '修改用户',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rule_code` (`rule_code`),
  UNIQUE KEY `uniq_tenant_id_rule_name` (`tenant_id`,`rule_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='规则表';

CREATE TABLE `rule_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租户ID',
  `rule_code` varchar(18) NOT NULL COMMENT '规则编码',
  `filter_col` varchar(100) NOT NULL COMMENT '规则条件的字段',
  `filter_detail` json NOT NULL COMMENT '条件需要满足的详情',
  `created_by` varchar(100) NOT NULL DEFAULT '' COMMENT '创建用户',
  `updated_by` varchar(100) NOT NULL DEFAULT '' COMMENT '修改用户',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `tenant_id_rule_code` (`tenant_id`,`rule_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='规则详情表';

-- 日志按tenant_id分表,保留18个月的数据
CREATE TABLE `rule_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属租户ID',
  `log_code` varchar(18) NOT NULL COMMENT '日志编码',
  `rule_code` varchar(18) NOT NULL COMMENT '规则编码',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '操作类型：1.添加规则(默认启用);2.编辑规则;3.禁用规则;4.启用规则;',
  `content` json NOT NULL COMMENT '规则明细',
  `created_by` varchar(100) NOT NULL DEFAULT '' COMMENT '创建用户',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `log_code` (`log_code`),
  KEY `tenant_id_rule_code` (`tenant_id`,`rule_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='规则日志表';
