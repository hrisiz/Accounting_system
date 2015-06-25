<?php
	if(isset($_POST['authenticate'])){
		if(!isset($_POST['account']['user']) || empty($_POST['account']['user'])):
			echo "<p class='show_error'>Моля въведете потребител.</p>";
			goto error;
		elseif(!isset($_POST['account']['password']) || empty($_POST['account']['password'])):
			echo "<p class='show_error'>Моля въведете парола.</p>";
			goto error;
		endif;
		$check = $db_conn->prepare('Select * From Account Where user_name=:user AND password = :password');
		$_POST['account']['password'] = hash("sha512","PowerPass-".$_POST['account']['password']);
		$check->execute($_POST['account']);
		if(count($check->fetchAll()) > 0){
			$_SESSION['USER'] = $_POST['account']['user'];
		}else{
			echo "<p class='show_error'>Грешно име или парола</p>";
			goto error;
		}
	}
	error:
	if(!isset($_SESSION['USER'])){
		require 'errors/authenticate.html';
		exit();
	}
