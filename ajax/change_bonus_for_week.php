<?php
$prep = $db_conn->prepare("Update Bonus Set money_per_week = :new_val Where id = :send_id");
$prep->execute($_POST) or die("Възника проблем. Моля свържете се с администратора.");