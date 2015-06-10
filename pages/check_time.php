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
			<td><button>Проверка</button></td>
			<td><input type="checkbox"/></td>
		</tr>
		<?php
		}
		?>
	</table>
</div>
<div id="compare">
	<h1>Сравнение</h1>
	<table>
		<?php
			$prep = $db_conn->prepare("Select * From Person");
			$prep->execute();
			$money_sum = 100;
			$persons = $prep->fetchAll(PDO::FETCH_ASSOC);
			echo "<tr><th>Дата</th>";
			foreach($persons as $person){
				echo "<th>".$person['first_name']." ".substr($person['family'],0,2)."</th>";
			}
			echo "</tr>";
			$prep = $db_conn->prepare("Select work_date From Work Group by work_date ");
			$prep->execute();
			$all_for_person = array_fill(0 , count($persons) , Array('time'=>0,'money'=>0) );
			foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $row){
				echo "<tr>";
				echo "<td>".date("d/m/Y", strtotime($row['work_date']))."</td>";
				$counter = 0;
				foreach($persons as $person){
					$prep = $db_conn->prepare("Select * From Work Where work_date = :date AND person_id = :person_id ");
					$prep->execute(Array('date'=>$row['work_date'],'person_id'=>$person['id']));
					$work_day = $prep->fetch();
					if(empty($work_day)){
						echo "<td>Няма</td>";
					}else{
						$day_time = ((strtotime($work_day['end_time']) - strtotime($work_day['start_time'])));
						$day_money = $day_time/60  * ($work_day['money_per_hour']/60);
						echo "<td>".date("H:i", strtotime($work_day['start_time']))." - ".date("H:i", strtotime($work_day['end_time']))." ( ".date("H:i", strtotime($work_day['end_time']) - strtotime($work_day['start_time']." +1 hour"))."ч. ) - ".$work_day['money_per_hour']." ($day_money лв.) </td>";
						$all_for_person[$counter]['time'] += $day_time;
						$all_for_person[$counter]['money'] += $day_money;
					}
					$counter++;
				}
				echo "</tr>";
			}
			echo "<tr><td></td>";
			foreach($all_for_person as $person_week_info){
				$hours = floor($person_week_info['time']/60/60);
				echo "<td>Общо: ".($hours).":".($person_week_info['time']/60 - $hours*60)." - ".$person_week_info['money']."</td>";
			}
			echo "</tr>";
		?>
	</table>
</div>