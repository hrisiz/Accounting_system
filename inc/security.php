<?php
// if (defined('WEB_INDEX')) {header("Location: /?page=Modules_News");}
	function make_log($file_name, $text)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = date("[".$ip."]".'m/d/Y H:i:s', time())." ".$text."\r\n";
		$file = file_put_contents ('logs/'.$file_name .' ['. date('d_m_y', time()) . '].log', $text, FILE_APPEND);
	}
	
	function filter($value)
	{
		$arr_filter = array("'",'"',';',':','%','<','>','javascript');
		foreach($arr_filter as $filter)
		{
			if (strpos($value, $filter))
			{
				make_log('sys_security', 'value: ' . $value);
				header('Location: index.php');//
				return NULL;
				exit;
			}
		}
		$value = trim($value);
		$value = htmlspecialchars($value);
		return $value;
	}
	function walk(&$array){
		foreach($array as $key => $value)
		{
			if(is_array($value)){
				walk($value);
			}else{
				$array[$key] = filter($value);
			}
		}
	}
	if(isset($_GET))
	{
		walk($_GET);
	}
	
	if(isset($_POST))
	{
		walk($_POST);
	}
	
	if(isset($_SESSION))
	{
		walk($_SESSION);
	}
	/*
	if(isset($_REQUEST))
	{
		foreach($_REQUEST as $key => $value)
		{
			$_REQUEST[$key] = filter($value);
		}
	}*/