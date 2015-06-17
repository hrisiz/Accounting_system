<?php
	if(isset($_POST['edit_person_day'])){
		if(!isset($_POST['person_id'])):
			echo"<p class='error'>Не сте избрали човек.</p>";
			goto error;
		elseif(!validateDate($_POST['date'])):
			echo "<p class='error'>Грешно въведена дата.</p>";
			unset($_POST['date']);
			goto error;
		elseif(!isset($_POST['id'])):
			echo "<p class='error'>Проблем! Моля свържете се с администратора.</p>";
			goto error;
		endif;
		
		$check_date = $db_conn->prepare("Select Count(*) From Work Where work_date = :work_date AND person_id = :person_id AND id <> :id");
		$input = Array(
			'id' => $_POST['id'],
			'person_id' =>$_POST['person_id'],
			'work_date'=>$_POST['date'],
		);
		$check_date->execute($input);
		
		$check_person = $db_conn->prepare("Select Count(*) From Person Where id = :person_id");
		$check_person->execute(Array('person_id' =>$_POST['person_id'],));
		
		$check_work_day = $db_conn->prepare("Select Count(*) From Work Where id = :id");
		$check_work_day->execute(Array('id' =>$_POST['id'],));
		
		if($check_date->fetch()[0] > 0):
			echo "<p class='error'>Този човек вече е записан за тази дата</p>";
			goto error;
		elseif($check_person->fetch()[0]<= 0):
			echo "<p class='error'>Невалидно въведен човек.</p>";
			goto error;
		elseif($check_work_day->fetch()[0]<= 0):
			echo "<p class='error'>Невалиден ред. Моля свържете се с администратора.</p>";
			goto error;
		endif;
		$input['start_time'] = $_POST['start_time']['hour'].":".$_POST['start_time']['minute'];
		$input['end_time'] = $_POST['end_time']['hour'].":".$_POST['end_time']['minute'];
		$input['free_time'] = $_POST['free_time']['hour'].":".$_POST['free_time']['minute'];
		$input['money_per_hour'] = $_POST['money_per_hour'];
			
		$prep = $db_conn->prepare("Update Work Set person_id = :person_id, work_date = :work_date, start_time = :start_time, end_time = :end_time, free_time = :free_time, money_per_hour = :money_per_hour Where id=:id");
		$prep->execute($input);
		echo "<p class='success'>Успешно редактирахте.</p>";
	}	
	if(isset($_POST['delete_person_day'])){
		$input = Array(
			'id' =>$_POST['id']
		);
		$check_work_day = $db_conn->prepare("Select Count(*) From Work Where id = :id");
		$check_work_day->execute($input);
		if($check_work_day->fetch()[0]<= 0):
			echo "<p class='error'>Невалиден ред. Моля свържете се с администратора.</p>";
			goto error;
		endif;
		$prep = $db_conn->prepare("Delete From Work Where id=:id");
		$prep->execute($input);
		echo "<p class='success'>Успешно изтрихте.</p>";
	}
	error:
?>
<div id="show_all">
	<?php
		$prep = $db_conn->prepare("Select  Person.*,
		SEC_TO_TIME(SUM(TIME_TO_SEC(Work.work_time))) as work_time,
		FORMAT(SUM(Work.work_money),2) as money 
		From Person Left Join Work On Work.person_id = Person.id 
		Group by Person.id");
		$prep->execute(); 
	?>
	<table>
		<tr><th>Име</th><th>Време</th><th>Пари</th><th>Проверка</th><th>Сравни</th></tr>
		<?php
		foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $row){
		?>
		<tr>
			<td><?=$row['first_name']?> <?=$row['family']?></td>
			<td><?=$row['work_time']?></td>
			<td><?=$row['money']?></td>
			<td><button data-id="<?=$row['id']?>" class="show_person">Проверка</button></td>
			<td><input type="checkbox" class="compare_person" data-id="<?=$row['id']?>"/></td>
		</tr>
		<?php
		}
		?>
	</table> 
</div>
<h1>Сравнение</h1>
<div id="compare">

</div>
<div class="front_js_show_page" id="person_info">
	
</div>