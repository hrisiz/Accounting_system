<?php
	if(isset($_POST['add_time'])){
		if(!isset($_POST['person'])){
				echo"<p>Не сте избрали хора.</p>";
				goto error;
		}
		$db_conn->beginTransaction();
		$money = ":money";
		if($_POST['money'] == 0){
			$money = "(Select money_per_hour From Person Where id=:person)";
		}
		foreach($_POST['person'] as $person_id => $value){
			$query = "Insert Into Work(person_id , start_time , end_time , money_per_hour, work_date) 
							values(:person,:start_time,:end_time,$money,:date)";
			$prep = $db_conn->prepare($query);
			$input = Array(
				'person'=>$person_id,
				'start_time' => $_POST['start_time']['hour'].":".$_POST['start_time']['minute'],
				'end_time' => $_POST['end_time']['hour'].":".$_POST['end_time']['minute'],
				'money' => $_POST['money'],
				'date' => $_POST['date']
			);
			$prep->execute($input);
		}
		$db_conn->commit();
		echo "<p>Успешно беше добавено и записано време.</p>";
		error:
	}
?>
<div id="input">
	<form method="POST">
		<label for="datepicker">Дата:</label>
		<input type="text" id="datepicker" name="date" value="<?=date("Y-m-d",time());?>"/>
		<div id="ajax">
			<?php
				include "ajax/add_person_time_form.php";
			?>
		</div>
		<input type="submit" name="add_time" value="Запазване"/>
	</form>
</div>