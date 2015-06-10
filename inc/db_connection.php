<?php
	$db_conf = parse_ini_file("config/database.ini");
	$db_conn = new PDO($db_conf['Database type'].':host='.$db_conf['Host'].";dbname=".$db_conf['Database name'],$db_conf['User'],$db_conf['Password']);