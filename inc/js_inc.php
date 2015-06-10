<?php
	$page = "Home";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	$root = parse_ini_file("config/root.ini");
	if(isset($root[$page])){
		echo "<script src=\"javascripts/".$root[$page].".js\"></script>";
	}