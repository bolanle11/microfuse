<?php
$installer=$this;
$installer->startSetup();
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('faq/faq')};
	CREATE TABLE {$this->getTable('faq/faq')}  (
	  `question_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `category` varchar(11) NOT NULL,
	  `question` varchar(1000) NOT NULL DEFAULT '',
	  `answer` varchar(10000) NOT NULL DEFAULT '',
	  `status` smallint(6) NOT NULL DEFAULT '0',
	  `priority` int(10) DEFAULT '0',
	  `byuser` int(1) NOT NULL DEFAULT '0',
	  `customer_id` varchar(15) NOT NULL,
	  `customer_name` varchar(100) NOT NULL,
	  `customer_email` varchar(100) NOT NULL,
	  `notified` int(1) NOT NULL,
	  `store_id` varchar(30) NOT NULL DEFAULT '0',
	  `store_set` varchar(30) NOT NULL DEFAULT '0',
	  `created_time` datetime DEFAULT NULL,
	  `update_time` datetime DEFAULT NULL,
	  `views` varchar(1000) NOT NULL,
	  PRIMARY KEY (`question_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
	DROP TABLE IF EXISTS {$this->getTable('faq/category')};
	CREATE TABLE {$this->getTable('faq/category')}  (
	  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(100) NOT NULL,
	  `description` varchar(1000) NOT NULL,
	  `status` smallint(6) NOT NULL DEFAULT '0',
	  `priority` int(10) NOT NULL DEFAULT '0',
	  `store_id` varchar(30) NOT NULL,
	  `store_set` varchar(30) NOT NULL,
	  PRIMARY KEY (`cat_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
	");
$installer->endSetup();
?>
