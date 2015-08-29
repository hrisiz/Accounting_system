<?php 
	if(isset($_POST['add_bonus'])){
		if(!isset($_POST['Bonus']['person'])):
			echo "<p class='error'>Не е въведен човек.</p>";
			goto error;
		elseif(!isset($_POST['Bonus']['type'])):
			echo "<p class='error'>Грешно въведен тип.</p>";
			goto error;
		endif;
		$check_person = $db_conn->prepare('Select * From Person Where id=:person_id');
		$check_person->execute(Array('person_id'=>$_POST['Bonus']['person']));
		$check_type = $db_conn->prepare('Select * From Bonus_type Where id=:type');
		$check_type->execute(Array('type'=>$_POST['Bonus']['type']));
		
		if(count($check_person->fetchAll(PDO::FETCH_ASSOC)) <= 0):
			echo "<p class='error'>Възникна проблем! Моля свържете се с администратора.</p>";
			goto error;
		elseif(count($check_type->fetchAll(PDO::FETCH_ASSOC)) <= 0):
			echo "<p class='error'>Възникна проблем! Моля свържете се с администратора.</p>";
			goto error;
		elseif($_POST['Bonus']['money'] < $_POST['Bonus']['money_per_week']):
			echo "<p class='error'>Взетите пари са повече от тези които трябва да бъдат върнати за седмица.</p>";
			goto error;
		endif;
		$prep = $db_conn->prepare("Insert Into Bonus(person_id,type,current_money,money,money_per_week,start_date) Values(:person,:type,:money,:money,:money_per_week,:start_date)");
		$prep->execute($_POST['Bonus']);
		echo "<p class='success'>Успешно записано.</p>";
	}
	if(isset($_POST['delete_bonus'])){
		if(!ctype_digit($_POST['id'])){
			echo "<p class='error'>Възникна проблем! Моля свържете се с администратора.</p>";
			goto error;
		}
		$check_person = $db_conn->prepare('Select * From Bonus Where id=:id');
		$check_person->execute(Array('id'=>$_POST['id']));
		if(count($check_person->fetchAll(PDO::FETCH_ASSOC)) <= 0):
			echo "<p class='error'>Възникна проблем! Моля свържете се с администратора.</p>";
			goto error;
		endif;
		$prep = $db_conn->prepare("Delete From Bonus Where id=:id");
		$prep->execute(Array('id'=>$_POST['id']));
	}
	error:
?>
<div id="input">
	<form method="POST">
		<label for="person">Човек:</label>
		<select id="person" name="Bonus[person]"/>
			<?php
				$persons = $db_conn->query('Select * From Person')->fetchAll();
				foreach($persons as $person){
			?>
					<option value="<?=$person['id']?>"><?=$person['first_name']?> <?=$person['second_name']?> <?=$person['family']?></option>
			<?php
				}
			?>
		</select>
		<label for="type">Тип:</label>
		<select id="type" name="Bonus[type]">
			<?php
				$bonus_type = $db_conn->query('Select * From Bonus_type')->fetchAll(PDO::FETCH_ASSOC);
				foreach($bonus_type as $type){
			?>
					<option value="<?=$type['id']?>"><?=$type['name']?></option>
			<?php
				}
			?>
		</select>
		<label for="money">Пари:</label>
		<input id="money" type="number" step="0.01" name="Bonus[money]"/>
		<label for="money_per_week">Пари на седмица:</label>
		<input id="money_per_week" type="number" step="0.01" name="Bonus[money_per_week]"/>
		<label for="start_date">Дана на взимане:</label>
		<input id="start_date" type="text" class="datepicker" name="Bonus[start_date]" value="<?=(isset($_POST['date']) ? $_POST['date']:date("Y-m-d",time()))?>"/>
		<input type="submit" name="add_bonus" value="Записване"/>
	</form>
</div>
<div id="show">
	<table>
		<thead>
			<tr>
				<th>Човек</th>
				<th>Тип</th>
				<th>Останали пари</th>
				<th>Начални пари</th>
				<th>Пари на седмица</th>
				<th>Дата на взимане</th>
				<th>Изтриване</th>
			</tr>
		</thead>
		<?php
			$bonuses = $db_conn->query("Select * From Bonus") or die("<p class='error'>Проблем с базата данни. Свържете се с администратора.</p>");
			$bonuses = $bonuses->fetchAll(PDO::FETCH_ASSOC);
			foreach($bonuses as $bonus){
				$person = $db_conn->query("Select * From Person Where id=".$bonus['person_id']) or die("<p class='error'>Проблем с базата данни. Свържете се с администратора.</p>");;
				$person = $person->fetch(PDO::FETCH_ASSOC);
				$type = $db_conn->query("Select * From Bonus_type Where id=".$bonus['type']) or die("<p class='error'>Проблем с базата данни. Свържете се с администратора.</p>");;
				$type = $type->fetch(PDO::FETCH_ASSOC);
		?>
		<tr>
			<td><?=$person['first_name']?> <?=$person['family']?></td>
			<td><?=$type['name']?></td>
			<td><?=$bonus['current_money']?></td>
			<td><?=$bonus['money']?></td>
			<td><?=$bonus['money_per_week']?></td>
			<td><?=$bonus['start_date']?></td>
			<td><form method="POST"> <input type="hidden" name="id" value="<?=$bonus['id']?>"/><input onclick="if(!confirm('Сигурни ли сте, че искате да го изтриете?')){return false;};" type="submit" name="delete_bonus" value="X"/></form></td>
		</tr>
		<?php
			}
		?>
	</table>
</div>