<?php
	$page = "Home";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	$root = parse_ini_file("config/root.ini");
	if(isset($root[$page])){
		$file_name = "css/".$root[$page].".css";
		if(file_exists($file_name)){
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$file_name."\">";
		}
	}