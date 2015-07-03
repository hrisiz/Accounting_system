<?php
$prep = $db_conn->prepare("Update Bonus Set use_now = :new_val Where id = :send_id");
$prep->execute($_POST) or die("Възника проблем. Моля свържете се с администратора.");
echo "Успешно беше направена промяна.";