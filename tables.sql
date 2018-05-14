CREATE TABLE `user_info` (
  `user_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(64) NOT NULL DEFAULT '',
  `user_name` VARCHAR(64) NOT NULL DEFAULT '',
  `password` CHAR(32) NOT NULL DEFAULT '',
  `user_type` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `user_code` VARCHAR(64) NOT NULL DEFAULT '',
  `organization_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `user_class` VARCHAR(64) NOT NULL DEFAULT '',
  `phone` VARCHAR(32) NOT NULL DEFAULT '',
  `address` VARCHAR(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  KEY `idx_organization_id` (`organization_id`),
  KEY `idx_user_name` (user_name),
  KEY `idx_email` (email)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `group_info` (
  `group_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(128) NOT NULL DEFAULT '',
  `group_admin_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `organization_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`),
  KEY `idx_organization_id` (`organization_id`),
  UNIQUE KEY `unq_group_admin` (`group_admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `group_user` (
  `id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `join_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_group_user` (`group_id`,`user_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_user` (`group_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `instrument_info` (
  `instrument_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `instrument_name` VARCHAR(128) NOT NULL DEFAULT '',
  `address` VARCHAR(128) NOT NULL DEFAULT '',
  `model_number` VARCHAR(64) NOT NULL DEFAULT '',
  -- 型号
  `specifications` VARCHAR(128) NOT NULL DEFAULT '',
  -- 规格
  `price` DECIMAL(10,3) NOT NULL DEFAULT 0,
  `status` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `appointment_price` DECIMAL(10,3) NOT NULL DEFAULT 0,
  `produce_country` VARCHAR(64) NOT NULL DEFAULT '',
  `manufacturer` VARCHAR(128) NOT NULL DEFAULT '',
  -- 生产厂家
  `manufacture_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  -- 出厂时间
  `purchase_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  -- 购置时间
  `organization_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT 0,
  -- 所属组织
  `type_number` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  -- 分类号
  `instrument_code` VARCHAR(128) NOT NULL DEFAULT '',
  -- 仪器编号
  `qualification` VARCHAR(512) NOT NULL DEFAULT '',
  -- 技术指标
  `instrument_function` VARCHAR(512) NOT NULL DEFAULT '',
  -- 功能
  `attachments` VARCHAR(512) NOT NULL DEFAULT '',
  -- 附件
  PRIMARY KEY (`instrument_id`),
  KEY `idx_type_number` (`type_number`)，
  UNIQUE KEY `unq_instrument_code` (`instrument_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `follow_instrument` (
  `id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `instrument_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_user_instrument` (`user_id`,`instrument_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `instrument_admin` (
  `id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `instrument_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_instrument_admin` (`user_id`,`instrument_id`),
  UNIQUE KEY `idx_instrument_id` (`instrument_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `appointment` (
  `appointment_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `theme` VARCHAR(128) NOT NULL DEFAULT '',
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `admin_user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `group_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `instrument_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `start_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `end_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `expenses` DECIMAL(10,3) NOT NULL DEFAULT 0,
  `appointment_comment` VARCHAR(255) NOT NULL DEFAULT '',
  `appointment_feedback` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`appointment_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_instrument_id` (`instrument_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `user_blacklist` (
  `id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `admin_user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `blacklist_type` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `instrument_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  -- fixme
  `create_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_user_blacklist` (`admin_user_id`,`user_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `reimbursement` (
  `reimbursement_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `appointment_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `admin_user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `instrument_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `group_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  `expenses` DECIMAL(10,3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`reimbursement_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`),
  KEY `idx_instrument_id` (`instrument_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `message`(
  `message_id` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(10) UNSIGNED NOT NULL DEFAULT 0,
  `content` VARCHAR(1024) NOT NULL DEFAULT '',
  `create_time` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `status` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`message_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;