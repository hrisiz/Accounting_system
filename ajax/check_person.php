<?php
	$prep = $db_conn->prepare("Select  Person.*,
		cast(SUM((Work.end_time - Work.start_time)) as time) as work_time,
		SUM(((floor((Work.end_time - Work.start_time)/100/100)*60) + floor((Work.end_time - Work.start_time)/100)%100) * (Work.money_per_hour/60)) as money 
		From Person Left Join Work On Work.person_id = Person.id   Where Person.id= :send_id
		Group by Person.id");
	$prep->execute($_POST); 
	$person_info = $prep->fetchAll(PDO::FETCH_ASSOC)[0];
?>
<ul id="person_info">
	<li>
		<p>Име:</p>
		<p><?=$person_info['first_name']?></p>
	</li>
	<li>
		<p>Презиме:</p>
		<p><?=$person_info['second_name']?></p>
	</li>
	<li>
		<p>Фамилия:</p>
		<p><?=$person_info['family']?></p>
	</li>
	<li>
		<p>Електронна поща:</p>
		<p><?=$person_info['email']?></p>
	</li>
	<li>
		<p>Адрес:</p>
		<p><?=$person_info['address']?></p>
	</li>
	<li>
		<p>Телефон:</p>
		<p><?=$person_info['phone']?></p>
	</li>
	<li>
		<p>Пари на час:</p>
		<p><?=$person_info['money_per_hour']?> лева/час</p>
	</li>
	<li>
		<p>Време на работа за седмицата:</p>
		<p><?=$person_info['work_time']?> ч.</p>
	</li>
	<li>
		<p>Пари за седмицата:</p>
		<p><?=$person_info['money']?> лв.</p>
	</li>
</ul>
<?php
	$prep = $db_conn->prepare("Select *,
		cast((Work.end_time - Work.start_time) as time) as work_time,
		((floor((Work.end_time - Work.start_time)/100/100)*60) + floor((Work.end_time - Work.start_time)/100)%100) * (Work.money_per_hour/60) as money 
		From Work  Where person_id= :send_id order by work_date");
	$prep->execute($_POST); 
	$person_work_info = $prep->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Работни дни</h1>
<table id="work_time_info">
	<tr>
		<th>Дата</th>
		<th>Начало</th>
		<th>Край</th>
		<th>Пари на час</th>
		<th>Време за деня</th>
		<th>Пари за деня</th>
		<th>Редактиране</th>
	</tr>
	<?php
		foreach($person_work_info as $day){
	?>
		<tr>
			<td><?=$day['work_date']?></td>
			<td><?=$day['start_time']?></td>
			<td><?=$day['end_time']?></td>
			<td><?=$day['money_per_hour']?></td>
			<td><?=$day['work_time']?></td>
			<td><?=$day['money']?></td>
			<td><button class="edit_person_day" data-id="<?=$day['id']?>">Редактиране</button></td>
		</tr>
	<?php
		}
	?>
</table>