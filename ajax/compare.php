﻿<table>
	<?php
		$input = "";
		foreach($_POST['send_ids'] as $key=>$value){
			$input .= "id = :".$key." OR ";
		}
		$input = substr($input,0,-3);
		$prep = $db_conn->prepare("Select * From Person Where ".$input);
		$prep->execute($_POST['send_ids']);
		$money_sum = 100;
		$persons = $prep->fetchAll(PDO::FETCH_ASSOC);
		echo "<tr><th>Дата</th>";
		foreach($persons as $person){
			echo "<th>".$person['first_name']."</th>";
		}
		echo "</tr>";
		$prep = $db_conn->prepare("Select MAX(work_date) as end,MIN(work_date) as start From Work ");
		$prep->execute();
		$all_for_person = array_fill(0 , count($persons) , Array('time'=>0,'money'=>0) );
		//foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $row){
		$dates = $prep->fetch(PDO::FETCH_ASSOC);
		for($i = strtotime($dates['start']); $i <= strtotime($dates['end']); $i = strtotime(date("Y-m-d",$i )."+1 day")){
			echo "<tr>";
			echo "<td>".date("d/m/Y", $i)."</td>";
			$counter = 0;
			foreach($persons as $person){
				$prep = $db_conn->prepare("Select *,
				cast(SUBTIME(SUBTIME(Work.end_time , Work.start_time),Work.free_time) as time) as work_time,
				HOUR(cast(SUBTIME(SUBTIME(Work.end_time , Work.start_time),Work.free_time) as time)) as hours,
				MINUTE(cast(SUBTIME(SUBTIME(Work.end_time , Work.start_time),Work.free_time) as time)) as minutes
				From Work Where work_date = :date AND person_id = :person_id ");
				$prep->execute(Array('date'=>date("Y-m-d",$i),'person_id'=>$person['id']));
				$work_day = $prep->fetch();
				if(empty($work_day)){
					echo "<td>Няма</td>";
				}else{
					$day_time = floor($work_day['hours'])*60 + floor($work_day['minutes']);
					$day_money = $day_time  * ($work_day['money_per_hour']/60);
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
			$hours = floor($person_week_info['time']/60/60);
			echo "<td>Общо: ".($hours).":".($person_week_info['time']/60 - $hours*60)." - ".$person_week_info['money']."</td>";
		}
		echo "</tr>";
	?>
</table>