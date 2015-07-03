<div id="return_value">
	<?php
		if(isset($_POST['create_person'])){
			$insert_array = $_POST['person'];
			if(!isset($insert_array['first_name']) || empty($insert_array['first_name'])){
				echo "<p>Error</p>";
				goto error;
			}
			$db_conn->beginTransaction();
			$prep = $db_conn->prepare("Insert Into Person(first_name,second_name,family,email,address,phone,money_per_hour) values( :first_name , :second_name , :family , :email , :address , :phone,:money_per_hour)");
			
			$prep->execute($insert_array);
			$db_conn->commit();
			echo "<p class='success'>Успешно беше добавен и записан ".$insert_array['first_name'].".</p>";
		}
	?>
	<?php
		if(isset($_POST['delete_person'])){
			$input = Array('id'=>$_POST['id']);
			$check_person = $db_conn->prepare("Select Count(*) From Person Where id = :id");
			$check_person->execute($input);
			if($check_person->fetch()[0] <= 0):
				echo "<p class='error'>Проблем. Моля свържете се с администратора.</p>";
				goto error;
			endif;
			$db_conn->beginTransaction();
				$delete_person = $db_conn->prepare("Delete From Person Where id = :id");
				$delete_person->execute($input);
				$delete_person_time = $db_conn->prepare("Delete From Work Where person_id = :id");
				$delete_person_time->execute($input);
			$db_conn->commit();
			echo "<p class='success'>Успешно беше изтрит време.</p>";
		}
	?>
	<?php
		if(isset($_POST['edit_person'])){
			$input = Array('id'=>$_POST['id']);
			
			$check_person = $db_conn->prepare("Select Count(*) From Person Where id = :id");
			$check_person->execute($input);
			
			if($check_person->fetch()[0] <= 0):
				echo "<p class='error'>Проблем. Моля свържете се с администратора.</p>";
				goto error;
			endif;
			
			$person_db_info = $db_conn->prepare("Select * From Person Where id=:id");
			$person_db_info->execute($input);
			
			$person_db_info = $person_db_info->fetch(PDO::FETCH_ASSOC);
			$result_array = array_diff($_POST['person'],$person_db_info);
			foreach($result_array as $col => $val){
				if(!isset($person_db_info[$col])){
					echo "<p class='error'>Въведена е грешна колона. Моля свържете се с администратора.</p>";
					goto error;
				}
			}
			$result_array['id'] = $_POST['id'];
			$query = "Update Person Set ";
			foreach($result_array as $column => $value){
				$query .= $column." = :".$column.",";
			}
			$query = substr($query, 0, -1);
			$query .= " Where id=:id";
			$prep = $db_conn->prepare($query);
			$prep->execute($result_array);
			echo "<p class='success'>Успешно беше редактиран ".$_POST['person']['first_name'].".</p>";
		}
		error:
	?>
</div>
<div id="input">
	<form method="POST">
		<label for="name">Име:</label>
		<input type="text" id="name" name="person[first_name]"/>
		<label for="name">Второ име:</label>
		<input type="text" id="name" name="person[second_name]"/>
		<label for="name">Фамилия:</label>
		<input type="text" id="name" name="person[family]"/>
		<label for="name">Електронна поща:</label>
		<input type="email" id="name" name="person[email]"/>
		<label for="address">Адрес:</label>
		<input type="text" id="address" name="person[address]"/>
		<label for="phone">Телефон:</label>
		<input type="text" id="phone" name="person[phone]"/>
		<label for="Money">Пари на час:</label>
		<input type="number" step="0.0001" id="money" name="person[money_per_hour]"/>
		<input type="submit" name="create_person" value="Създаване"/>
	</form>
</div>
<?php
	$prep = $db_conn->prepare("Select Count(*) From Person");
	$prep->execute();
	if($prep->fetch()[0] > 0){
?>
<div id="show">
	<table>
		<thead>
			<tr>
			<th>Номер</th>
			<th>Име</th>
			<th>Второ име</th>
			<th>Фамилия</th>
			<th>Електронна поща</th>
			<th>Адрес</th>
			<th>Телефон</th>
			<th>Пари на час</th>
			<th>Остатък</th>
			<th>Изтриване</th>
			<th>Редактиране</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$prep = $db_conn->prepare("Select * From Person");
				$prep->execute();
				foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $row){
			?>
			<tr>
				<?php
					foreach($row as $value){
				?>
				<td><?=$value?></td>
			<?php
					}
			?>
			<td><form method="post"><input type="hidden" name="id" value="<?=$row['id']?>"><button onclick="if(!confirm('Сигурни ли сте, че искате да изтрие този човек?')){return false;};" id="delete_person" type="submit" name="delete_person">X</button></form></td>
			<td><button id="edit_person" data-id="<?=$row['id']?>">Редактиране</button></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</div>
<?php
	}
?>
<div class="front_js_show_page" id="edit_person_field_in">

</div>