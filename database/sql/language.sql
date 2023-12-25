-- common 配置  不区分租户  全局支持的语言列表

CREATE TABLE `country_language` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `country_code` varchar(255) NOT NULL DEFAULT '' COMMENT '国家编码',
  `language_code` varchar(50) NOT NULL DEFAULT '' COMMENT '语言编码',
  `currency_name_code` varchar(50) NOT NULL DEFAULT '' COMMENT '币种名称',
  `currency_icon_code` varchar(50) NOT NULL DEFAULT '' COMMENT '币种符号',
  `country_flag` varchar(255) NOT NULL DEFAULT '' COMMENT '国旗',
  `country_icon` varchar(255) NOT NULL DEFAULT '' COMMENT '国家icon',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用（1:启用   2:禁用）',
  `created_by` varchar(25) NOT NULL DEFAULT '' COMMENT '创建用户',
  `updated_by` varchar(25) NOT NULL DEFAULT '' COMMENT '修改用户',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='国家-语言配置';
