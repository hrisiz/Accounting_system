<?php
	$prep = $db_conn->prepare("Select  Person.*,
		SEC_TO_TIME(SUM(TIME_TO_SEC(Work.work_time))) as work_time,
		FORMAT(SUM(Work.work_money),2) as money 
		From Person Left Join Work On Work.person_id = Person.id Where Person.id= :send_id
		Group by Person.id");
	$prep->execute(Array('send_id'=>$_POST['send_id'])); 
	$person_info = $prep->fetchAll(PDO::FETCH_ASSOC);
	if(count($person_info) <= 0){
		echo "<p class='error'>Проблем! Моля свържете се с администратора.</p>";
		exit();
	}
	$person_info = $person_info[0];
	$person_info['money'] = explode(".",$person_info['money']);
?>
<div id="options">
	<button id="previous_person">Предишен</button>
	<button id="print_person">Принтиране</button>
	<button id="send_end_week">Завършване</button>
	<button id="next_person">Следжащ</button>
</div>
<?php
	if(!validateDate($_POST['send_end_date']) && !empty($_POST['send_end_date'])):
		echo "<p class='error'>Грешно въведена дата за краен ден</p>";
		goto error;
	endif;
	$days = $db_conn->query("Select MAX(work_date) as last From Work")->fetch(PDO::FETCH_ASSOC);

	if(!empty($_POST['send_end_date'])){
		$days['last'] = $_POST['send_end_date'];
	}
	$days['send_id'] = $_POST['send_id'];
	$select_person_bonuses_query = "Select Bonus.*,Bonus_type.name as type_name From Bonus Left Join Bonus_type On Bonus_type.id = Bonus.type Where 
	Bonus.start_date <= :last AND 
	Bonus.person_id = :send_id";
	// $select_person_bonuses_query = "Select 
	// Bonus.*,
	// Bonus_type.name as type_name 
	// From Bonus Left Join Bonus_type On Bonus_type.id = Bonus.type 
	// Where Bonus.person_id = :send_id";
	$prep = $db_conn->prepare($select_person_bonuses_query);
	$prep->execute($days);
	// $prep->execute(Array('send_id'=>$_POST['send_id']));
?>
<ul id="person_info">
	<li>
		<p>Име:</p>
		<p><?=$person_info['first_name']?> <?=$person_info['second_name']?> <?=$person_info['family']?></p>
	</li>
	<li  class="not_for_print">
		<p>Пари на час:</p>
		<p><?=$person_info['money_per_hour']?> лева/час</p>
	</li>
	<li>
		<p>Време на работа за седмицата:</p>
		<p><?=$person_info['work_time']?> ч.</p>
	</li>
	<li>
		<p>Пари за седмицата:</p>
		<p><?=$person_info['money'][0]?> лв.</p>
	</li>
	<li class="not_for_print">
		<p>Останали пари от седмиците:</p>
		<p><?=((($person_info['money'][1]/100) + $person_info['balance']) >= REWRITE_BALANCE ? ((($person_info['money'][1]/100) + $person_info['balance'])-REWRITE_BALANCE):(($person_info['money'][1]/100) + $person_info['balance']))?> лв.</p>
	</li>
	<?php
		$take_money = 0;
		foreach($prep->fetchAll(PDO::FETCH_ASSOC) as $bonus){
			$pay_now = $bonus['money_per_week'];
			if($bonus['money_per_week'] > $bonus['current_money']){
				$pay_now = $bonus['current_money'];
			}
			if($bonus['use_now'] != 0){
				$take_money += $pay_now;
			}
	?>
	<li <?=(($bonus['use_now'] == 0)? "class=\"not_used_bonus\"":"")?>>
		<p><?=$bonus['type_name']?>:</p>
		<p><input data-takemoney="<?=$pay_now?>" data-id="<?=$bonus['id']?>" type="checkbox" class="bonus" <?=(($bonus['use_now'] != 0)? "checked":"")?>/></p>
		<p>
			<ul>
				<li>
					<p>Взети пари:</p>
					<p><?=$bonus['money']?> лв.</p>			
				</li>
				<li class="pay_now" <?=(($bonus['use_now'] == 0) ? "style=\"display:none\"" : "");?>>
					<p>Изплатени сега:</p>
					<p><span><input data-id="<?=$bonus['id']?>" id="take_this_week" value="<?=$pay_now?>"/></span> лв.</p>
				</li>
				<li class="remaining_money">
					<p>Оставащи пари:</p>
					<p><span><?=($bonus['current_money']-(($bonus['use_now'] != 0) ? $pay_now:0))?></span> лв.</p>			
				</li>
			</ul>
		</p>
	</li>
	<?php
		}
	?>
	<li>
		<p>Дадени пари:</p>
		<p id="end_money_for_week"><span><?=(($person_info['money'][0] - $take_money)+((($person_info['money'][1]/100) + $person_info['balance']) >= REWRITE_BALANCE ? REWRITE_BALANCE:0))?></span> лв.</p>
	</li>
</ul>
<?php
	$prep = $db_conn->prepare("Select * From Work Where person_id= :send_id order by work_date");
	$prep->execute(Array('send_id'=>$_POST['send_id'])); 
	$person_work_info = $prep->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="not_for_print">Работни дни</h1>
<table class="not_for_print" id="work_time_info">
	<tr>
		<th>Дата</th>
		<th>Начало</th>
		<th>Край</th>
		<th class="not_for_print">Пари на час</th>
		<th>Време за деня</th>
		<th>Пари за деня</th>
	</tr>
	<?php
		foreach($person_work_info as $day){
	?>
		<tr>
			<td><?=$day['work_date']?></td>
			<td><?=$day['start_time']?></td>
			<td><?=$day['end_time']?></td>
			<td class="not_for_print"><?=$day['money_per_hour']?></td>
			<td><?=$day['work_time']?></td>
			<td><?=$day['work_money']?></td>
		</tr>
	<?php
		}
	?>
</table>
<?php
error:
?>