<?php
	include "inc.php";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		echo "<p>Cannot load undefined page!</p>";
	}
	$root = parse_ini_file("config/ajax_root.ini");
	if(isset($root[$page])){
		include "ajax/".$root[$page].".php";
	}else{
		echo "<p>Error 404 Page not found.</p>";
	}