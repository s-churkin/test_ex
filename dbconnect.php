<?php
$connection = new mysqli('localhost', 'root', '', 'yii2basic');
if (mysqli_connect_errno()) {
    printf("Подключиться к БД не удалось: %s\n", mysqli_connect_error());
    exit();
}
