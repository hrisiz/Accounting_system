<?php
	$prep = $db_conn->prepare("Select * From Work Where id=:send_id");
	$prep->execute($_POST);
	$row = $prep->fetch(PDO::FETCH_ASSOC);
	$prep = $db_conn->prepare("Select * From Person");
	$prep->execute();
	$persons = $prep->fetchAll(PDO::FETCH_ASSOC);
?>
<form method="POST">
	<input type="hidden" name="id" value="<?=$row['id']?>"/>
	<select name="person_id">
		<?php
			foreach($persons as $person){
				$selected = "";
				if($person['id'] == $row['person_id']){
					$selected = "selected";
				}
		?>
			<option value="<?=$person['id']?>" <?=$selected?>><?=$person['first_name']?> <?=$person['second_name']?> <?=$person['family']?></option>
		<?php
			}
		?>
	</select>
	<label for="datepicker">Дата:</label>
	<input type="text" id="datepicker" name="date" value="<?=date("Y-m-d",strtotime($row['work_date']));?>"/>
	<div id="time">
			<div id="start_time">
				<label for="end_time_h">Начало на работния ден:</label>
				<div class="clear"></div>
				<select id="start_time_h" name="start_time[hour]">
					<?php
						for($i = 0; $i <= 24;$i++){
							$selected = "";
							if($i == date('H',strtotime($row['start_time']))){
								$selected = "selected";
							}
					?>
						<option <?=$selected?>><?=$i?></option>
					<?php
						}
					?>
				</select>
				<select name="start_time[minute]">
					<?php
						for($i = 0; $i < 60;$i += 5){
							$selected = "";
							if($i ==  date('i',strtotime($row['start_time']))){
								$selected = "selected";
							}
					?>
						<option <?=$selected?>><?=$i?></option>
					<?php
						}
					?>
				</select>
			</div>
			<div id="end_time" >
				<label for="end_time_h">Край на работния ден:</label>
				<div class="clear"></div>
				<select id="end_time_h" name="end_time[minute]">
					<?php
						for($i = 0; $i < 60;$i += 5){
							$selected = "";
							if($i ==date('i',strtotime($row['end_time']))){
								$selected = "selected";
							}
					?>
						<option <?=$selected?>><?=$i?></option>
					<?php
						}
					?>
			</select>
				<select id="end_time_h" class="time" name="end_time[hour]">
					<?php
						for($i = 0; $i <= 24;$i++){
							$selected = "";
							if($i == date('H',strtotime($row['end_time']))){
								$selected = "selected";
							}
					?>
						<option <?=$selected?>><?=$i?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<label>Пари на час:</label>
		<input type="number"  step="0.0001" name="money_per_hour" value="<?=$row['money_per_hour']?>"/>
		<input type="submit" name="edit_person_day" value="Редактирай" />
		<input type="submit" onclick="if(!confirm('Сигурни ли сте, че искате да изтрие тази дата за този човек?')){return false;};" name="delete_person_day" value="Изтриване" />
		<input type="button" class="show_person" data-id="<?=$row['person_id']?>" value="Отказ" />
</form>