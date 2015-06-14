<?php 
	if(isset($_POST['end_week'])){
		//date check
		$input = Array("start_date"=>$_POST['start_date'],"end_date"=>$_POST['end_date']);
		$query_where_date = "";
		if(empty($_POST['start_date'])){
			if(empty($_POST['end_date'])){
				unset($input['start_date']);
				unset($input['end_date']);
			}else{
				$query_where_date = "AND Work.work_date <= :end_date";
				unset($input['start_date']);
			}
		}elseif(empty($_POST['end_date'])){
				$query_where_date = "AND Work.work_date >= :start_date";
				unset($input['end_date']);
		}else{
				$query_where_date = "AND Work.work_date >= :start_date AND Work.work_date <= :end_date";
		}
		$select_persons_query = "Select 
		Person.*,
		cast(SUM((Work.end_time - Work.start_time)) as time) as work_time,
		SUM(((floor((Work.end_time - Work.start_time)/100/100)*60) + floor((Work.end_time - Work.start_time)/100)%100) * (Work.money_per_hour/60)) as money 
		From Person Left Join Work On Work.person_id = Person.id ".$query_where_date." Group by Work.person_id";
		$prep = $db_conn->prepare($select_persons_query);
		$prep->execute($input);
		foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $person){
			$output ="Име: ".$person['first_name']."\r\nПрезиме: ".$person['second_name']."\r\nФамилия: ".$person['family']."\r\nЕлектронна поша: ".$person['email']."\r\nАдрес: ".$person['address']."\r\nТелефон: ".$person['phone']."\r\nПари на час: ".$person['money_per_hour']."\r\nВреме на работа за седмицата: ".$person['work_time']."\r\nПари за седмицата: ".$person['money']."\r\n\r\nНачало,Край,Пари на час,Дата,Работно време,Пари \r\n";
			$output .= "\r\n";
			$input["person_id"] = $person['id'];
			$select_query =  "Select 
			start_time,
			end_time,
			money_per_hour,
			work_date,cast((Work.end_time - Work.start_time) as time) as work_time,
		   ((floor((Work.end_time - Work.start_time)/100/100)*60) + floor((Work.end_time - Work.start_time)/100)%100) * (Work.money_per_hour/60) as money 
			From Work Where Work.person_id = :person_id ".$query_where_date." order by work_date";
			$prep1 = $db_conn->prepare($select_query);
			$prep1->execute($input);
			foreach($prep1->fetchAll(PDO::FETCH_ASSOC) as $row){
				$output .= implode("," , $row);
				$output .= "\r\n";
				$output .= "\r\n";
			}
			$days = $db_conn->query("Select MAX(work_date) as last,MIN(work_date) as first From Work")->fetch(PDO::FETCH_ASSOC);
			//file_put_contents("history/".$person['first_name']."".$days['first']."=-=".$days['last'].".log",$output); 
			file_put_contents("history/Тестиме.log","Някакъв текст");
			//file_put_contents("history/". $text.".log",$output);
		}
	}
?>
<div>
	<form method="POST">
		<label>Начало на седмицата:</label>
		<input type="text" class="datepicker" name="start_date"/>
		<label>Край на седмицата:</label>
		<input type="text" class="datepicker" name="end_date"/>
		<input type="submit" name="end_week" value="Изчисти"/>
	</form>
</div>