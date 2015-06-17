<?php
	if(isset($_POST['add_time'])){
		
		if(!isset($_POST['person'])):
			echo"<p class='error'>Не сте избрали хора.</p>";
			goto error;
		elseif(!validateDate($_POST['date'])):
			echo "<p class='error'>Грешно въведена дата.</p>";
			unset($_POST['date']);
			goto error;
		endif;
		foreach($_POST['person'] as $person_id => $value){
			$person_check = $db_conn->prepare("Select * From Person Where id = :person");
			$person_check->execute(Array('person' => $person_id));
			$person_check = $person_check->fetch();
			
			$date_check = $db_conn->prepare("Select Count(*) From Work Where person_id=:person AND date = :date");
			$date_check->execute(Array('person' => $person_id , 'date'=>$_POST['date']));
			$date_check = $date_check->fetch()[0];
			if($person_check <= 0):
				echo "<p class='error'>Човекът с номер ".$person_id." не съществува.</p>";
				goto error;
			elseif($date_check <= 0):
			echo "<p class='error'>Вече има въведено време за ".$person_check['first_name']." ".$person_check['family']." на ".$_POST['date'].".</p>";
				goto error;
			endif;
		}
		
		$db_conn->beginTransaction();
		$money = ":money";
		if($_POST['money'] == 0){
			$money = "(Select money_per_hour From Person Where id=:person)";
		}
		foreach($_POST['person'] as $person_id => $value){
			$query = "Insert Into Work(person_id , start_time , end_time , free_time , money_per_hour, work_date) 
							values(:person,
							:start_time,
							:end_time,
							:free_time,
							$money,
							:date)";
			$prep = $db_conn->prepare($query);
			$input = Array(
				'person'=>$person_id,
				'start_time' => $_POST['start_time']['hour'].":".$_POST['start_time']['minute'],
				'end_time' => $_POST['end_time']['hour'].":".$_POST['end_time']['minute'],
				'free_time' => $_POST['free_time']['hour'].":".$_POST['free_time']['minute'],
				'money' => $_POST['money'],
				'date' => $_POST['date'],
			);
			$prep->execute($input);
		}
		$db_conn->commit();
		echo "<p class='success'>Успешно беше добавено и записано време.</p>";
	}
	error:
?>
<div id="input">
	<form method="POST">
		<label for="datepicker">Дата:</label>
		<input type="text" id="datepicker" name="date" value="<?=(isset($_POST['date']) ? $_POST['date']:date("Y-m-d",time()))?>"/>
		<div id="ajax">
			<?php
				include "ajax/add_person_time_form.php";
			?>
		</div>
		<input type="submit" name="add_time" value="Запазване"/>
	</form>
</div>