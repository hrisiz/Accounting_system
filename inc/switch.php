<?php
	$page = "Home";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	$root = parse_ini_file("config/root.ini");
	if(isset($root[$page])){
		include "pages/".$root[$page].".php";
	}else{
		header('Location:errors/404.html');
	}