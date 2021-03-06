﻿<?php
	if(isset($_POST['end_week'])){
		$db_conn->beginTransaction();
		if(!validateDate($_POST['start_date']) && !empty($_POST['start_date'])):
			echo "<p class='error'>Грешно въведена дата за начален ден</p>";
			goto error;
		elseif(!validateDate($_POST['end_date']) && !empty($_POST['end_date'])):
			echo "<p class='error'>Грешно въведена дата за краен ден</p>";
			goto error;
		endif;
		$days = $db_conn->query("Select MAX(work_date) as last,MIN(work_date) as first From Work")->fetch(PDO::FETCH_ASSOC);
		if(!empty($_POST['start_date'])){
			$days['first'] = $_POST['start_date'];
		}
		if(!empty($_POST['end_date'])){
			$days['last'] = $_POST['end_date'];
		}
		$select_persons_query = "Select 
		Person.*,
		SEC_TO_TIME(SUM(TIME_TO_SEC(Work.work_time))) as work_time,
		FORMAT(SUM(Work.work_money),2) as money 
		From Person Left Join Work On Work.person_id = Person.id AND Work.work_date >= :first AND Work.work_date <= :last Group by Work.person_id";
		$prep = $db_conn->prepare($select_persons_query);
		$prep->execute($days);
		foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $person){
			$used_bonuses = $db_conn->prepare("Select SUM(money_per_week) as money_for_week From Bonus Where person_id = :person_id AND use_now = 1 AND start_date <= :last");
			$used_bonuses->execute(Array('person_id'=>$person['id'],'last'=>$days['last']));
			$used_bonuses = $used_bonuses->fetch(PDO::FETCH_ASSOC);
			$balance_money = explode(".",$person['money']);
			$person['money'] = $balance_money[0];
			if(($person['money'] - $used_bonuses['money_for_week']) < 0){
				echo "<p class='error'>".$person['first_name']." ".$person['family']." е с отрицателни пари!".($person['money'] - $used_bonuses['money_for_week'])."</p>";
				continue;
			}
			if(1){
				$person_balance = $db_conn->prepare("Update Person Set balance = balance + :balance Where id=:person_id");
				$person_balance->execute(Array('balance'=>isset($balance_money[1]) ? $balance_money[1]/100:0,'person_id'=>$person['id']));
			}
			$person['money'] = floor($person['money']);
			$output ="Име: ".$person['first_name']."\r\nПрезиме: ".$person['second_name']."\r\nФамилия: ".$person['family']."\r\nЕлектронна поша: ".$person['email']."\r\nАдрес: ".$person['address']."\r\nТелефон: ".$person['phone']."\r\nПари на час: ".$person['money_per_hour']."\r\nВреме на работа за седмицата: ".$person['work_time']."\r\nПари за седмицата: ".$person['money']."\r\nПари удържани от заем/аванс:".$used_bonuses['money_for_week']."\r\n\r\n\r\nДата,Начало,Край,Пари на час,Работно време,Пари \r\n";
			$output .= "\r\n";
			$select_query =  "Select 
			work_date,
			start_time,
			end_time,
			money_per_hour,
			work_time,
			work_money
			From Work Where Work.person_id = :person_id AND Work.work_date >= :first AND Work.work_date <= :last order by work_date";
			$prep1 = $db_conn->prepare($select_query);
			$prep1->execute(array_merge($days,Array('person_id'=>$person['id'])));
			foreach($prep1->fetchAll(PDO::FETCH_ASSOC) as $row){
				$output .= implode("," , $row);
				$output .= "\r\n";
				$output .= "\r\n";
			}
			file_put_contents("history/".iconv("UTF-8", "Windows-1251",$person['first_name'])."-".$days['first']."=-=".$days['last'].".log",$output); 
			$del = $db_conn->prepare("Delete from Work Where Work.work_date >= :first AND Work.work_date <= :last AND person_id = :person_id");
			$bonuses = $db_conn->prepare("Update Bonus Set current_money = current_money - money_per_week Where use_now = 1 AND start_date <= :last AND person_id = :person_id");
			$del->execute(Array('first' => $days['first'],'last'=>$days['last'],'person_id'=>$person['id']));
			$bonuses->execute(Array('last'=>$days['last'],'person_id'=>$person['id']));
		}
		
		//$bonuses_use = $db_conn->prepare("Update Bonus Set use_now = 1 Where start_date > :last");
		$del_bonuses = $db_conn->prepare("Delete From Bonus Where current_money <= 0");
		$rewrite_balance = $db_conn->prepare("Update Person Set balance = balance - :rewrite_balance Where balance >= :rewrite_balance");
		//$bonuses_use->execute(Array('last'=>$days['last']));
		$del_bonuses->execute();
		$rewrite_balance->execute(Array('rewrite_balance'=>REWRITE_BALANCE));
		$db_conn->commit();
		echo "<p class='success'>Седмицата беше успешно завършена.</p>";
	}
	error:
?>
<div>
	<form id="end_week_form" method="POST">
		<label>Начало на седмицата:</label>
		<input type="text" class="datepicker" id="start_date" name="start_date"/>
		<label>Край на седмицата:</label>
		<input type="text" class="datepicker" id="end_date" name="end_date"/>
		<input type="button" id="end_week_with_info" value="Провери и завърши"/>
		<input type="submit" name="end_week" value="Изчисти"/>
	</form>
</div>

<div class="front_js_show_page" id="end_week_people_info">

</div>