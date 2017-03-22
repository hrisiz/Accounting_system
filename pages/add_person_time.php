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
			
			$date_check = $db_conn->prepare("Select COUNT(*) From Work Where person_id = :person AND work_date = :date");
			$date_check->execute(Array('person' => $person_id , 'date'=>$_POST['date']));
			$date_check = $date_check->fetch()[0];
			if($person_check <= 0):
				echo "<p class='error'>Човекът с номер ".$person_id." не съществува.</p>";
				goto error;
			elseif($date_check > 0):
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
				include_once "ajax/add_person_time_form.php";
			?>
			
		</div>
		<div id="time">
			<div id="start_time">
				<label for="end_time_h">Начало на работния ден:</label>
				<div class="clear"></div>
				<select id="start_time_h" name="start_time[hour]">
					<?=time_pick_values(24,(isset($_POST['start_time']['hour']) ? $_POST['start_time']['hour']:8));?>
				</select>
				<select name="start_time[minute]">
					<?=time_pick_values(59,(isset($_POST['start_time']['minutes']) ? $_POST['start_time']['minutes']:0),5);?>
				</select>
			</div>
			<div id="end_time" >
				<label for="end_time_h">Край на работния ден:</label>
				<div class="clear"></div>
				<select id="end_time_m" name="end_time[minute]">
					<?=time_pick_values(59,0,5);?>
			</select>
				<select id="end_time_h" class="time" name="end_time[hour]">
					<?=time_pick_values(24,19);?>
				</select>
			</div>
			<div id="free_time" >
					<label for="free_time_h">Почивка:</label>
					<div class="clear"></div>
					<select id="free_time_h" class="time" name="free_time[hour]">
						<?=time_pick_values(24,1);?>
					</select>
					<select id="free_time_m" name="free_time[minute]">
						<?=time_pick_values(59,0,5);?>
					</select>
					<button type="button" id="freeday">Почивка</button>
			</div>
		<div class="clear"></div>
		<label for="money">Пари на час:</label>
		<input type="number" step="0.0001" id="money" name="money"/>
		</div>
		<input type="submit" id="add_time" name="add_time" value="Запазване"/>
	</form>
</div>