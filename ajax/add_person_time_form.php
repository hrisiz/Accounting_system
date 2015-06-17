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
		