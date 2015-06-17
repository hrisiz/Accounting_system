<?php
	function validateDate($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') == $date;
	}
	function time_pick_values($end, $selected_val = 0, $inc = 1, $start = 0){
		$result = "";
		for($i = $start; $i <= $end;$i+=$inc){
			$selected = "";
			if($i == $selected_val){
				$selected = "selected";
			}
			$result .= "<option $selected>$i</option>";
		}
		return $result;
	}