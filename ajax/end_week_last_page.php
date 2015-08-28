<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
if(!validateDate($_POST['send_start_date']) && !empty($_POST['send_start_date'])):
	echo "<p class='error'>Грешно въведена дата за начален ден</p>";
	goto error;
elseif(!validateDate($_POST['send_end_date']) && !empty($_POST['send_end_date'])):
	echo "<p class='error'>Грешно въведена дата за краен ден</p>";
	goto error;
endif;
$days = $db_conn->query("Select MAX(work_date) as last,MIN(work_date) as first From Work")->fetch(PDO::FETCH_ASSOC);
if(!empty($_POST['send_start_date'])){
	$days['first'] = $_POST['send_start_date'];
}
if(!empty($_POST['send_end_date'])){
	$days['last'] = $_POST['send_end_date'];
}
$select_persons_query = "Select 
Person.*
From Person Inner Join Work On Work.person_id = Person.id AND Work.work_date >= :first AND Work.work_date <= :last Group by Work.person_id";
$prep = $db_conn->prepare($select_persons_query);
$prep->execute($days) or die ("Възникна проблем моля свържете се с администратора");

$outp = "[";
foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $person){
	$outp .= $person['id'].", ";
}
$outp = substr($outp,0,-2)."]";
echo $outp;
error:
?>