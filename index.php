<?php
	include "inc.php";
?>
<html>
	<head>
		<meta charset="UTF-8">
		<?php include "inc/css_inc.php";?>
		<link rel="stylesheet" type="text/css" href="css/design.css">  
		<link rel="stylesheet" href="javascripts/jquery-ui-smoothness/jquery-ui.css">
		<script src="javascripts/jquery-2.1.4.min.js"></script>
		<script src="javascripts/jquery-ui-smoothness/jquery-ui.js"></script>
		<script src="javascripts/main.js"></script>
		<?php include "inc/js_inc.php";?>
		<meta name="description" content="Blog">
		<meta name="keywords" content="Blog">
		<meta name="author" content="Grizis">
	</head>
	<body>
		<header>
			<nav>
				<a href="?page=PersonCreate">Добавяне на човек</a> 
				<a href="?page=AddPersonTime">Добавяне на време</a> 
				<a href="?page=CheckTime">Проверка</a> 
				<a href="/jquery/">jQuery</a>
			</nav>
		</header>
		<section>
			<div class="back_js_show_page">
			</div>
			<?php include "inc/switch.php";?>
		</section>
		<footer>
			<p>Create by Grizis!</p>
		</footer>
	</body>
</html>