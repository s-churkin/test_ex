<?php
require('cform.php');
/*
HOME : http://localhost/test_ex/
 */
$page   = (string)filter_input(INPUT_GET, 'page');
$action = (string)filter_input(INPUT_GET, 'action');
$id     = (int)filter_input(INPUT_GET, 'id');
if (!empty((string)filter_input(INPUT_POST, 'id'))) {
    $id = (string)filter_input(INPUT_POST, 'id');
}
if (!empty((string)filter_input(INPUT_POST, 'action'))) {
    $action = (string)filter_input(INPUT_POST, 'action');
}
$user = (string)filter_input(INPUT_COOKIE, 'user');
$captcha = (string)filter_input(INPUT_COOKIE, 'captcha');

$count_entrys   = 0  ; // количество записей
$entry_on_page  = 5;  // количество записей на странице
$current_page   = (string)filter_input(INPUT_GET, 'current_page');;  // текущая страница
if (empty($current_page)) {
    $current_page = 1;
}
$max_pages_list = 5; // сколько номеров страниц показывать

if ($page == 'logout') {
    setcookie("user", ''); 
    $user = '';
    $page == 'home';
}

// echo 'page=' . $page . ' action=' . $action . ' id=' . $id . ' $user=' . $user . ' ' . (string)filter_input(INPUT_POST, 'captcha') . ' ' . $captcha;

$error = false;
$cform = new cform();
$cform->bldHeader('Гостевая книга');
if ($page == 'formcreate') {
    if ($action == 'view') {
        include ("dbconnect.php");
        $r = mysqli_query($connection, 'SELECT * FROM `tst_gbook` WHERE id=' . $id);
        $row = mysqli_fetch_array($r);
        $fields[] = ['#', 3, 'id', 9, 'text', $row['id'], '', 'hidden="hidden"'];
        $fields[] = ['Имя пользователя:', 3, 'name', 9, 'text', $row['name'], '', 'readonly="readonly"'];
        $fields[] = ['Адрес электронной почты:', 3, 'email', 9, 'text', $row['email'], '', 'readonly="readonly"'];
        $fields[] = ['Заголовок сообщения:', 3, 'subject', 9, 'text', $row['subject'], '', 'readonly="readonly"'];
        $fields[] = ['Текст сообщения:', 3, 'body', 9, 'textarea', $row['body'], '', 'readonly="readonly"'];
        $connection->close();  
        if ($user == 'admin') {
        $buttons[] = ['Удалить', 'button', 'btn btn-danger', './?page=formcreate&action=delete&id=' . $id];
        }
        $buttons[] = ['Вернуться', 'button', 'btn btn-default', './'];
        $cform->bldMenu([['Главная', 'active', './'], ['Ввести сообщение', '', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', '', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
        $cform->bldForm('Гостевая книга', 'Просмотр сообщения', 10, $fields, $buttons);
    }
    elseif ($action == 'create' || $action == 'validate') {
        $fields[] = ['Имя пользователя:', 3, 'name', 9, 'text', '', '', ''];
        $fields[] = ['Адрес электронной почты:', 3, 'email', 9, 'text', '', '', ''];
        $fields[] = ['Заголовок сообщения:', 3, 'subject', 9, 'text', '', '', ''];
        $fields[] = ['Текст сообщения:', 3, 'body', 9, 'textarea', '', '', ''];
        $fields[] = ['Введите: <img src=\'captcha.php\'/> ', 3, 'captcha', 9, 'text', ' ', '', ''];

        $buttons[] = ['Сохранить', 'submit', 'btn btn-success', ''];
        $buttons[] = ['Вернуться', 'button', 'btn btn-default', './'];
        if ($action == 'validate') {
            foreach ($fields as $key => $field) {
                if (!empty((string) filter_input(INPUT_POST, $field[2]))) {
                    $fields[$key][5] = (string) filter_input(INPUT_POST, $field[2]);
                } else {
                    $error = true;
                    $fields[$key][6] = 'Значение поля не должно быть пустым';
                }
            }
            if (!filter_var($fields[1][5], FILTER_VALIDATE_EMAIL) && !empty($fields[1][5])) {
                $error = true;
                $fields[1][6] = 'E-mail (' . $fields[1][5] . ') задан некорректно.';
            }
            if ($captcha !== (string)filter_input(INPUT_POST, 'captcha')) {
                $error = true;
                $fields[4][6] = 'Неверно введены символы капчи';
            }
        }
        if ($action == 'create' || $error == true) {
            $cform->bldMenu([['Главная', '', './'], ['Ввести сообщение', 'active', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', '', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
            $cform->bldForm('Гостевая книга', 'Ввод сообщения', 10, $fields, $buttons);
        } else { // Ошибок нет 
            $action = ''; 
            $cform->bldMenu([['Главная', 'active', './'], ['Ввести сообщение', '', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', '', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
            include ("dbconnect.php");
            mysqli_query($connection, "INSERT INTO `tst_gbook`(`name`, `email`, `subject`, `body`) VALUES ('" . $fields[0][5] . "','" . $fields[1][5] . "','" . $fields[2][5] . "', '" . $fields[3][5] . "')");
            $connection->close();  
        }
    }
    if ($action == 'delete') {
        if (!empty($id)) {
            include ("dbconnect.php");
            mysqli_query($connection, "DELETE FROM `tst_gbook` WHERE `id` = " . $id);
            $connection->close();
        }
       $action = ''; 
       $cform->bldMenu([['Главная', 'active', './'], ['Ввести сообщение', '', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', '', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
    }
}
elseif($page == 'formlogin') {
    $fields[] = ['Имя', 2, 'name', 10, 'text', '', '', '',];
    $fields[] = ['Пароль', 2, 'password', 10, 'password', '', '', '',];
    $buttons[] = ['Войти', 'submit', 'btn btn-success', ''];
    $buttons[] = ['Отмена', 'button', 'btn btn-default', './'];
    if ($action == 'validate') {
        foreach($fields as $key=>$field) {
            if (!empty((string)filter_input(INPUT_POST, $field[2]))) {
                $fields[$key][5] = (string)filter_input(INPUT_POST, $field[2]);
            } else {
                $error = true;
                $fields[$key][6] = 'Значение поля не должно быть пустым';
            }
        }
        if ($error == false && ((string)filter_input(INPUT_POST, 'name') !== 'admin' || (string)filter_input(INPUT_POST, 'password') !== 'admin')) {
            $error = true;
            $fields[$key][6] = 'Неверные имя/пароль';
        }
    }
    if ($action == 'login' || $error == true) {
        $cform->bldMenu([['Главная', '', './'], ['Ввести сообщение', '', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', 'active', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
        $cform->bldForm('Гостевая книга', 'Регистрация пользователя', 4, $fields, $buttons);
    } else { // Ошибок нет 
        setcookie('user', 'admin' , time() + 3600); 
        $action = '';
        $user = 'admin';
        $cform->bldMenu([['Главная', 'active', './'], ['Ввести сообщение', '', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', '', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
    }
} else {
    $cform->bldMenu([['Главная', 'active', './'], ['Ввести сообщение', '', './?page=formcreate&action=create']], [(!empty($user)) ? 'Выйти (' . $user . ')' : 'Войти', '', (!empty($user)) ? './?page=logout' : './?page=formlogin&action=login']);
}
if (empty($action) && !$error) {
    $fields = [];
    $fields[] = ['#', 'id',];
    $fields[] = ['Имя', 'name',];
    $fields[] = ['Эл.почта', 'email',];
    $fields[] = ['Тема', 'subject',];
    $fields[] = ['Текст', 'body',];
//        $fields[] = ['./img/0.png', 'captcha',];
    $buttons[] = ['Создать запись', 'button', 'btn btn-success', './?page=home&action=create'];
    $buttons[] = ['Отмена', 'button', 'btn btn-default', './'];
    include ("dbconnect.php");
    $count_entrys = mysqli_fetch_array(mysqli_query($connection, 'SELECT COUNT(*) as count FROM `tst_gbook`'))[0];
    $rows = mysqli_query($connection, 'SELECT * FROM `tst_gbook` LIMIT ' . (int) $entry_on_page . ' OFFSET ' . (int) $current_page);
    while ($row = mysqli_fetch_array($rows)) {
        $r[] = [$row['id'],
            $row['name'],
            $row['email'],
            $row['subject'],
            ((strlen($row['body']) > 100) ? (substr($row['body'], 0, 100) . '...') : ($row['body'])),
            '<a href="' . './?page=formcreate&action=view&id=' . $row['id'] . '" title="Просмотр сообщения" aria-label="Просмотр" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a>',
        ];
    }
    $connection->close();
    $cform->bldTable('Гостевая книга', 'Список сообщений', 12, $fields, $r, $buttons, $count_entrys, $entry_on_page, $current_page, $max_pages_list);
}
$cform->bldFutter();
