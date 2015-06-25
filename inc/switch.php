<?php
	$page = "Home";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	$root = parse_ini_file("config/root.ini");
	if(isset($root[$page])){
		if(file_exists("pages/".$root[$page].".php")){
			include "pages/".$root[$page].".php";
		}else{
			header('Location:errors/404.html');
		}
	}else{
		header('Location:errors/404.html');
	}