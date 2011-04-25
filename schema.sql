--
-- Table structure for table `mailqueue`
--

CREATE TABLE IF NOT EXISTS `mailqueue` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `state` enum('pending','sent','failed') NOT NULL default 'pending',
  `sender_name` varchar(128) default NULL,
  `sender_email` varchar(320) NOT NULL,
  `recipient_name` varchar(128) default NULL,
  `recipient_email` varchar(320) NOT NULL,
  `subject` varchar(78) default NULL,
  `body` text NOT NULL,
  `priority` smallint(5) unsigned NOT NULL default '1' COMMENT 'Higher priority is a larger number. Priority 5 is higher than priority 1.',
  `attempts` smallint(5) unsigned NOT NULL default '0',
  `created` datetime NOT NULL,
  `sent` datetime default NULL,
  `failed` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;