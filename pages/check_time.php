<?php
	if(isset($_POST['edit_person_day'])){
		$prep = $db_conn->prepare("Select * From Work Where work_date = :work_date AND person_id = :person_id AND id <> :id");
		$input = Array(
			'id' => $_POST['id'],
			'person_id' =>$_POST['person_id'],
			'work_date'=>$_POST['date'],
		);
		$prep->execute($input);
		if(count($prep->fetchAll()) > 0){
			echo "Този човек вече е записан за тази дата";
			goto error;
		}
		$input['start_time'] = $_POST['start_time']['hour'].":".$_POST['start_time']['minute'];
		$input['end_time'] = $_POST['end_time']['hour'].":".$_POST['end_time']['minute'];
		$input['free_time'] = $_POST['free_time']['hour'].":".$_POST['free_time']['minute'];
		$input['money_per_hour'] = $_POST['money_per_hour'];
			
		$prep = $db_conn->prepare("Update Work Set person_id = :person_id, work_date = :work_date, start_time = :start_time, end_time = :end_time, free_time = :free_time, money_per_hour = :money_per_hour Where id=:id");
		$prep->execute($input);
		echo "Успешно редактирахте.";
		error:
	}	
	if(isset($_POST['delete_person_day'])){
		$input = Array(
			'id' =>$_POST['id']
		);
		$prep = $db_conn->prepare("Delete From Work Where id=:id");
		$prep->execute($input);
		echo "Успешно изтрихте.";
	}
?>
<div id="show_all">
	<?php
		$prep = $db_conn->prepare("Select  Person.*,
		cast(SUM(Work.work_time) as time)	 as work_time,
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