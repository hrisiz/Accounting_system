<?php 

?>
<div>
	<button>Добавяне</button>
	<button>Проверка</button>
</div>
<div>
	<form method="POST">
		<label for="person">Човек:</label>
		<select id="person" name="person"/>
			<?php
				$persons = $db_conn->query('Select * From Person')->fetchAll();
				foreach($persons as $person){
			?>
					<option value="<?=$person['id']?>"><?=$person['first_name']?> <?=$person['second_name']?> <?=$person['family']?></option>
			<?php
				}
			?>
		</select>
		<label for="type">Тип:</label>
		<select id="type" name="type">
			<option value="1">Аванс</option>
			<option value="2">Заем</option>
			<option value="3">Бонус</option>
		</select>
		<label for="money">Пари:</label>
		<input id="money" type="number" step="0.01" name="money"/>
		<label for="money_per_week">Пари на седмица:</label>
		<input id="money_per_week" type="number" step="0.01" name="money_per_week"/>
		<label for="return_date">Дана на връщане:</label>
		<input id="return_date" type="text" class="datepicker" name="return_date"/>
		<input type="submit" name="end_week" value="Изчисти"/>
	</form>
</div>