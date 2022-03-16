<?php
// Пример 8.28. Программа для ввода записей в таблицу dishes базы данных

// загрузить вспомогательный класс для составления форм
require 'FormHelper.php';

// подключиться к базе данных
try {
    $db = new PDO('mysql:host=localhost;dbname=test', 'root', '12345');
} catch (PDOException $e){
    print "Can't connect: " . $e->getMessage();
    exit;
}
// установить исключения при ошибках в базе данных
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Основная логика фукционирования страницы:
// - Если форма передана на обработку, проверить достоверность данных,
//   обработать их и снова отобразить форму.
// - Если форма не передана на обработку, отобразить ее снова
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Если функция validate_form() возвращает ошибки,
    // передать их функции show_form()
    list($errors, $input) = validate_form();
    if ($errors){
        show_form($errors);
    } else {
        // Переданные данные из формы достоверны, обработать их
        process_form($input);
    }
} else {
    // Данные из формы не переданы, отобразить ее снова
    show_form();
}

function show_form($errors = array()){
    // установить свои значения по умолчанию: цена состовляет 5 долларов США
    $defaults = array('price' => '5.00');

    // создать объект $form с надлежащими свойствами по умолчанию
    $form = new FormHelper($defaults);

    // Ради ясности весь код HTML-разметки и отображения формы вынесен в отдельный файл
    include 'insert-form.php';
}

function validate_form(){
    $input = array();
    $errors = array();

    // обязательное наименование блюда
    $input['dish_name'] = trim($_POST['dish_name'] ?? '');
    if (! strlen($input['dish_name'])) {
        $errors[] = 'Please enter the name of the dish.';
    }

    // цена должна быть указана достоверным положительным числом с плавающей точкой
    $input['price'] = filter_input(INPUT_POST, 'price'
                                    , FILTER_VALIDATE_FLOAT);
    if ($input['price'] < 0){
        $errors[] = 'Please enter a valid price.';
    }

    // по умолчанию в элементе ввода is_spicy устанавливается значение 'no'
    $input['is_spicy'] = $_POST['is_spicy'] ?? 'no';

    return array($errors, $input);
}

function process_form($input){
    // получить в этой функции доступ к глобальной переменной $db
    global $db;
    // установить в переменной $is_spicy значение в зависимости от состояния одноименного флажка
    if ($input['is_spicy'] == 'yes'){
        $is_spicy = 1;
    } else {
        $is_spicy = 0;
    }

    // ввести новое блюдо в таблицу базы данных
    try {
        $stmt = $db->prepare('insert into dishes (dish_name,
                                                        price,
                                                        is_spicy
                                                        values (?,?,?)');
        $stmt->execute(array($input['dish_name'],
                            $input['price'],
                            $is_spicy));
        // сообщить пользователю о вводе блюда в базу данных
        print 'Added ' . htmlentities($input['dish_name']) . 'to the database.';
    } catch (PDOException $e){
        print " Couldn't add your dish to the database.";
    }
}
