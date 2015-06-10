<div id="show_all">
	<?php
		$prep = $db_conn->prepare("Select  Person.*,
		cast(SUM((Work.end_time - Work.start_time)) as time) as work_time,
		SUM(((floor((Work.end_time - Work.start_time)/100/100)*60) + floor((Work.end_time - Work.start_time)/100)%100) * (Work.money_per_hour/60)) as money 
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
<div class="front_js_show_page" id="check_person">
	
</div>