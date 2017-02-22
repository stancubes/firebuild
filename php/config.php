<?php

	require_once('libs/Idiorm/idiorm.php');


	session_name('FireBuild');  
	session_start();


	ORM::configure('sqlite:firebuild.db');


	if($_SERVER['REMOTE_ADDR'] == '::1') {
		$server = 'local';
	}
	else {
		$server = 'prod';
	}


	switch($server) {

		case 'local':

			ORM::configure('mysql:host=localhost;dbname=firebuild');
			ORM::configure('username', 'root');
			ORM::configure('password', '');
			ORM::configure('logging', true);

		break;

		case 'prod':

			ORM::configure('mysql:host=localhost;dbname=firebuild');
			ORM::configure('username', 'root');
			ORM::configure('password', '2209*pec07');
			ORM::configure('logging', false);

		break;

	}


	ORM::configure('return_result_sets', true);
	ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8'));


?>