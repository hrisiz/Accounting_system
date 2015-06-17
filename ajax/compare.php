<table>
	<?php
		$input = "";
		foreach($_POST['send_ids'] as $key=>$value){
			echo $value;
			$input .= "id = :".$key." OR ";
		}
		$input = substr($input,0,-3);
		$prep = $db_conn->prepare("Select * From Person Where ".$input);
		$prep->execute($_POST['send_ids']);
		$money_sum = 100;
		$persons = $prep->fetchAll(PDO::FETCH_ASSOC);
		if(count($persons) <= 0){
			echo "<p class='error'>Проблем! Моля свържете се с администратора.</p>";
			exit();
		}
		echo "<tr><th>Дата</th>";
		foreach($persons as $person){
			echo "<th>".$person['first_name']."</th>";
		}
		echo "</tr>";
		$prep = $db_conn->prepare("Select MAX(work_date) as end,MIN(work_date) as start From Work ");
		$prep->execute();
		$all_for_person = array_fill(0 , count($persons) , Array('time'=>0,'money'=>0) );
		$dates = $prep->fetch(PDO::FETCH_ASSOC);
		for($i = strtotime($dates['start']); $i <= strtotime($dates['end']); $i = strtotime(date("Y-m-d",$i )."+1 day")){
			echo "<tr>";
			echo "<td>".date("d/m/Y", $i)."</td>";
			$counter = 0;
			foreach($persons as $person){
				$prep = $db_conn->prepare("Select *
				From Work Where work_date = :date AND person_id = :person_id ");
				$prep->execute(Array('date'=>date("Y-m-d",$i),'person_id'=>$person['id']));
				$work_day = $prep->fetch();
				if(empty($work_day)){
					echo "<td>Няма</td>";
				}else{
					$day_time = date('H',strtotime($work_day['work_time']))*60 + date('i',strtotime($work_day['work_time']));
					$day_money = $work_day['work_money'];
					echo "<td>".date("H:i", strtotime($work_day['start_time']))."
					- ".date("H:i", strtotime($work_day['end_time']))." ( 
					".date("H:i", strtotime($work_day['work_time']))."ч. ) - 
					".$work_day['money_per_hour']." ($day_money лв.) 
					</td>";
					$all_for_person[$counter]['time'] += $day_time;
					$all_for_person[$counter]['money'] += $day_money;
				}
				$counter++;
			}
			echo "</tr>";
		}
		echo "<tr><td></td>";
		foreach($all_for_person as $person_week_info){
			$hours = floor($person_week_info['time']/60);
			echo "<td>Общо: ".sprintf('%02d',($hours)).":".sprintf('%02d',($person_week_info['time']%60))."ч. - ".$person_week_info['money']."лв</td>";
		}
		echo "</tr>";
		error:
	?>
</table>