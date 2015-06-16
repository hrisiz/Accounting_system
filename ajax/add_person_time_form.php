<?php
	$date = date("Y-m-d",time());
	if(isset($_POST['date'])){
		$date = $_POST['date'];
	}
?>		
		<label for="person">Човек:</label>
		<input type="text" id="persons_input" value="Показване/Скриване" readonly />
		<div id="persons">
				<?php 
					$prep = $db_conn->prepare("Select Person.* From Person Left Join Work On Work.person_id = Person.id AND Work.work_date = :date Where Work.work_date is null");
					$input = Array('date'=>$date);
					$prep->execute($input);
					$persons_info = $prep->fetchAll(PDO::FETCH_ASSOC);
					if(count($persons_info) <= 0){
				?>
					<p>Няма хора на тази дата за които не е въведен работен ден.</p>
				<?php
					}
				?>
			<ul>
				<?php
					foreach($persons_info as $column => $value){
				?>
					<li><?=$value['first_name']?> <?=$value['second_name']?> <?=$value['family']?></li>
					<input type="checkbox" id="person[<?=$value['id']?>]" name="person[<?=$value['id']?>]" />
				<?php
					}
				?>
			</ul>
		</div>
		<div id="time">
			<div id="start_time">
				<label for="end_time_h">Начало на работния ден:</label>
				<div class="clear"></div>
				<select id="start_time_h" name="start_time[hour]">
					<?php
						for($i = 0; $i <= 24;$i++){
							$selected = "";
							if($i == 8){
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
							if($i == 0){
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
				<select id="end_time_m" name="end_time[minute]">
					<?php
						for($i = 0; $i < 60;$i += 5){
							$selected = "";
							if($i == 0){
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
							if($i ==19){
								$selected = "selected";
							}
					?>
						<option <?=$selected?>><?=$i?></option>
					<?php
						}
					?>
				</select>
			</div>
		<div id="free_time" >
				<label for="free_time_h">Почивка:</label>
				<div class="clear"></div>
				<select id="free_time_h" class="time" name="free_time[hour]">
					<?php
						for($i = 0; $i <= 24;$i++){
							$selected = "";
							if($i == 1){
								$selected = "selected";
							}
					?>
						<option <?=$selected?>><?=$i?></option>
					<?php
						}
					?>
				</select>
				<select id="free_time_m" name="free_time[minute]">
					<?php
						for($i = 0; $i < 60;$i += 5){
							$selected = "";
							if($i == 0){
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
		<div class="clear"></div>
		<label for="money">Пари на час:</label>
		<input type="number" step="0.0001" id="money" name="money"/>