<?php
	$prep = $db_conn->prepare("Select * From Person Where id=:id");
	$input = Array('id'=>$_POST['send_id']);
	$prep->execute($input);
	$person_info = $prep->fetch();
?>
	<form method="POST">
		<input type="hidden" name="id" value="<?=$person_info['id']?>"\>
		<label for="name">Име:</label>
		<input type="text" id="name" name="person[first_name]" value="<?=$person_info['first_name']?>"/>
		<label for="name">Второ име:</label>
		<input type="text" id="name" name="person[second_name]" value="<?=$person_info['second_name']?>"/>
		<label for="name">Фамилия:</label>
		<input type="text" id="name" name="person[family]" value="<?=$person_info['family']?>"/>
		<label for="name">Електронна поща:</label>
		<input type="email" id="name" name="person[email]" value="<?=$person_info['email']?>"/>
		<label for="address">Адрес:</label>
		<input type="text" id="address" name="person[address]" value="<?=$person_info['address']?>"/>
		<label for="phone">Телефон:</label>
		<input type="text" id="phone" name="person[phone]" value="<?=$person_info['phone']?>"/>
		<label for="Money">Пари на час:</label>
		<input type="number" step="0.0001" id="money" name="person[money_per_hour]" value="<?=$person_info['money_per_hour']?>"/>
		<input type="submit" name="edit_person" value="Редактиране"/>
	</form>