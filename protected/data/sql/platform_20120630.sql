-- 增加留存率表

set names 'utf8';

CREATE TABLE `retention_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '服务器ID',
  `current_day` date NOT NULL DEFAULT '0000-00-00' COMMENT '当前日期',
  `compare_day` date NOT NULL DEFAULT '0000-00-00' COMMENT '对比日期',
  `num` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Index_server_id` (`server_id`,`current_day`,`compare_day`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='留存率'