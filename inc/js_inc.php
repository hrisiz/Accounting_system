<?php
	$page = "Home";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	$root = parse_ini_file("config/root.ini");
	if(isset($root[$page])){
		$file_name = "javascripts/".$root[$page].".js";
		if(file_exists($file_name)){
			echo "<script src=\"".$file_name."\"></script>";
		}
	}