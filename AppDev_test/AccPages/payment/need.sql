CREATE TABLE `plans` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` float(10,2) NOT NULL,
  `interval` enum('DAY','WEEK','MONTH','YEAR') NOT NULL COMMENT 'DAY(365) | WEEK(52) | MONTH(12) | YEAR(1)',
  `interval_count` tinyint(2) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `user_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'foreign key of "users" table',
  `plan_id` int(5) DEFAULT NULL COMMENT 'foreign key of "plans" table',
  `paypal_order_id` varchar(255) DEFAULT NULL,
  `paypal_plan_id` varchar(255) DEFAULT NULL,
  `paypal_subscr_id` varchar(100) NOT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `paid_amount` float(10,2) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `subscriber_id` varchar(100) DEFAULT NULL,
  `subscriber_name` varchar(50) DEFAULT NULL,
  `subscriber_email` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

todo:
-relationships
-'update' user table lagyan ng `subscription_id` int(11) NOT NULL DEFAULT 0 COMMENT 'foreign key of "user_subscriptions" table',
