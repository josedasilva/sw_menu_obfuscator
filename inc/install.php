<?php

	$sql = 'CREATE TABLE IF NOT EXISTS `'.$swmo_table_name.'` (
	  `user_id` int(11) NOT NULL,
	  `menu_data` text COLLATE utf8_swedish_ci NOT NULL,
	  PRIMARY KEY (`user_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci';

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
