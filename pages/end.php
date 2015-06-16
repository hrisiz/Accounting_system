<?php 
	if(isset($_POST['end_week'])){
		//date check
		$days = $db_conn->query("Select MAX(work_date) as last,MIN(work_date) as first From Work")->fetch(PDO::FETCH_ASSOC);
		if(!empty($_POST['start_date'])){
			$days['first'] = $_POST['start_date'];
		}
		if(!empty($_POST['end_date'])){
			$days['last'] = $_POST['end_date'];
		}
		$select_persons_query = "Select 
		Person.*,
		cast(SUM(Work.work_time) as time)	 as work_time,
		FORMAT(SUM(Work.work_money),2) as money 
		From Person Left Join Work On Work.person_id = Person.id AND Work.work_date >= :first AND Work.work_date <= :last Group by Work.person_id";
		$prep = $db_conn->prepare($select_persons_query);
		$prep->execute($days);
		foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $person){
			$output ="Име: ".$person['first_name']."\r\nПрезиме: ".$person['second_name']."\r\nФамилия: ".$person['family']."\r\nЕлектронна поша: ".$person['email']."\r\nАдрес: ".$person['address']."\r\nТелефон: ".$person['phone']."\r\nПари на час: ".$person['money_per_hour']."\r\nВреме на работа за седмицата: ".$person['work_time']."\r\nПари за седмицата: ".$person['money']."\r\n\r\nНачало,Край,Пари на час,Дата,Работно време,Пари \r\n";
			$output .= "\r\n";
			$select_query =  "Select 
			start_time,
			end_time,
			money_per_hour
			From Work Where Work.person_id = :person_id AND Work.work_date >= :first AND Work.work_date <= :last order by work_date";
			$prep1 = $db_conn->prepare($select_query);
			$prep1->execute(array_merge($days,Array('person_id'=>$person['id'])));
			foreach($prep1->fetchAll(PDO::FETCH_ASSOC) as $row){
				$output .= implode("," , $row);
				$output .= "\r\n";
				$output .= "\r\n";
			}
			file_put_contents("history/".iconv("UTF-8", "Windows-1251",$person['first_name'])."-".$days['first']."=-=".$days['last'].".log",$output); 
		}
		$del = $db_conn->prepare("Delete from Work Where Work.work_date >= :first AND Work.work_date <= :last");
		$del->execute($days);
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