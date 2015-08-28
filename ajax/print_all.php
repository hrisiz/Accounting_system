<?php
$persons = $db_conn->query("Select  Person.*,
		SEC_TO_TIME(SUM(TIME_TO_SEC(Work.work_time))) as work_time,
		FORMAT(SUM(Work.work_money),2) as money 
		From Person Left Join Work On Work.person_id = Person.id
		Group by Person.id")->fetchAll(PDO::FETCH_ASSOC);

foreach($persons as $person){
	$select_person_bonuses_query = "Select Bonus.*,Bonus_type.name as type_name From Bonus Left Join Bonus_type On Bonus_type.id = Bonus.type Where 
	Bonus.start_date <= :last AND 
	Bonus.person_id = :send_id";
	$prep = $db_conn->prepare($select_person_bonuses_query);
	$prep->execute($days);
}
?>