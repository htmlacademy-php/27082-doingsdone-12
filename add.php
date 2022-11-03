<?php
///require_once ('helpers.php');
///
///

session_start();
if (!isset($_SESSION['id'])) {
    header ('Location: /');
    exit;
};
$userID = $_SESSION['id'];
$userName = $_SESSION['user'];



require_once ('helpers.php');
$ts = time();
//echo ($ts);
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$type2=[ "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
//подключение к базе данных, вывод ошибки
$con = mysqli_connect("localhost", "root", "", "doingsdone_db");
mysqli_set_charset($con, "utf8");
if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    //  print("Соединение установлено");
    // выполнение запросов
}

//тестовый поиск id (ПОСЛЕ ИНДЕКС PHP ВЫВОДИТ ЧТО ВВЕЛИ)
$cat_task_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//echo "T".$cat_task_id."ЕУЧЕ";


if(isset($cat_task_id)){
    //пачка для выводу нужного проекта
    // $sort_project="SELECT * FROM task WHERE USER=2 AND project_id=$cat_task_id";
    $task_usersql="SELECT * FROM project LEFT JOIN task on task.project_id=project.id where user_id=$userID and project_id=$cat_task_id ";
    $result_sql_task= mysqli_query($con, $task_usersql);
    $task_count1 = mysqli_fetch_all($result_sql_task , MYSQLI_ASSOC);
    //echo "<pre>";
    //print_r ($task_count1);
    //  echo "</pre>";
    //вывод по запросу
    if (!$task_count1){
        http_response_code(404);
    }

}
else  {

    $sort_project="SELECT * FROM task WHERE USER=$userID ";
    $sort_project_vivod=mysqli_query($con, $sort_project);
    $task_sql_current = mysqli_fetch_all($sort_project_vivod, MYSQLI_ASSOC);
    //oll
    $task_usersql_oll="SELECT * FROM project LEFT JOIN task on task.project_id=project.id where user_id=$userID ";
    $result1_oll = mysqli_query($con, $task_usersql_oll);
    $task_count_oll = mysqli_fetch_all($result1_oll, MYSQLI_ASSOC);
    $task_count1=0;
    $task_count1=$task_count_oll;
    //echo "<pre>";
//print_r ($task_count_oll);
//echo "</pre>";
}


$projectuser = "SELECT * FROM project where user_id=$userID";
//$projectuser1 = "SELECT * FROM project where id_user=2";
$taskuser ="SELECT * FROM task WHERE USER=$userID";
$name_nick="SELECT * FROM  users WHERE id=$userID";

$result2_oll_user = mysqli_query($con, $taskuser);
$task_count_oll2 = mysqli_fetch_all($result2_oll_user , MYSQLI_ASSOC);

// список задач с группами
//$task_usersql="SELECT * FROM project LEFT JOIN task on task.project_id=project.id where id_user=2 and project_id=$cat_task_id ";
//oll
$task_usersql_oll="SELECT * FROM project LEFT JOIN task on task.project_id=project.id where user_id=$userID ";
$result1_oll = mysqli_query($con, $task_usersql_oll);
$task_count_oll = mysqli_fetch_all($result1_oll, MYSQLI_ASSOC);
//echo "<pre>";
//print_r ($task_count_oll);
//echo "</pre>";
$result_name_nick = mysqli_query($con, $name_nick);
$sql_task_user= 'SELECT * FROM task WHERE `user`=$userID';
$result_sql_user= mysqli_query($con, $sql_task_user);
$result = mysqli_query($con, $projectuser);
//$result1 = mysqli_query($con, $task_usersql);


//пачка для выводу нужного проекта

//вывод по запросу

//itog for work


/*echo "<pre>";
print_r ($task_sql_current);
echo "</pre>";
*/
// список задач простым массивом из ассотиативного
//$task_sql = array_column ((mysqli_fetch_all($result, MYSQLI_ASSOC)),"title");

$task_sql2 = mysqli_fetch_all($result, MYSQLI_ASSOC);
//print_r ($task_sql2);
//$task_sql_project_id = array_column ((mysqli_fetch_all($result1, MYSQLI_ASSOC)),"id");
//print_r ($task_sql_project_id);

//$task_count = mysqli_fetch_all($task_sql_oll1 , MYSQLI_ASSOC);
//в актуальном разрезе

/*echo "<pre>";
print_r( $task_count."test");
echo "</pre>";
*/
//ник пользователя



//$result_name_nick1 =mysqli_fetch_all($result_name_nick, MYSQLI_ASSOC);
$result_name_nick3 = array_column ((mysqli_fetch_all($result_name_nick, MYSQLI_ASSOC)),"name");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $tsql_name =filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, ['options' => ['default' => '']]);
    if (!$tsql_name) {
        $errors['$tsql_name'] = 'Название не введено';
    }

    $result_name_nick3 = array_column ((mysqli_fetch_all($result_name_nick, MYSQLI_ASSOC)),"name");

//проверка что есть название

    $project_err = filter_input(INPUT_POST, 'project', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
//проверка даты
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING, ['options' => ['default' => '']]);
   // print_r ($errors);


    if ($date) {

        if (is_date_valid($date)) {
            if (strtotime($date) < strtotime('now')) {
                $errors['date'] = 'Выбрана прошедшая или уже наступившая дата';
            }
        } else {
            $errors['date'] = 'Дата не корректна';
        }
    } else {
        $errors['date'] = 'Дата не заполнена';
    };
    // Проверяем загрузил ли пользователь файл, получаем имя файла и его размер

  /*  if (isset($_FILES["file"]) && $_FILES['file']['name'] !== "") {

        $current_mime_type = mime_content_type($_FILES["file"]["tmp_name"]);
        $white_list_files = ["image/jpeg", "image/png", "text/plain", "application/pdf", "application/msword"];

        $file_name = $_FILES["file"]["name"];
        $file_size = $_FILES["file"]["size"];
        $tmp_name = $_FILES["file"]["tmp_name"];


        if (!in_array($current_mime_type, $white_list_files)) {
            $errors["file"] = "Загрузите файл в формате jpeg, png, txt, pdf или doc";
        }else if ($file_size > 200000) {
            $errors["user_file"] = "Максимальный размер файла: 200Кб";
        }
        else {
            // Сохраняем его в папке «uploads» и формируем ссылку на скачивание
            $file_path = __DIR__ . "/uploads/";
            $file_url = "/uploads/" . $file_name;

            // Функция move_uploaded_file($current_path, $new_path) проверяет, что файл действительно загружен через форму и перемещает загруженный файл по новому адресу
            move_uploaded_file($tmp_name, $file_path . $file_name);

            // Добавляем название файла в наш массив $task
            $task["file"] = $file_url;
        }
    }*/


    if (is_uploaded_file($_FILES['file']['tmp_name'])) { // была загрузка файла
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) { // Если загружен файл и нет ошибок, то сохраняем его в папку
            $original_name = $_FILES['file']['name'];
           // $errors['file'] = 'нето';
            $target = __DIR__  . '/uploads/' . $original_name;

            // сохраняем файл в папке
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                $errors['file'] = 'Не удалось сохранить файл.';
            }
        } else {
            $errors['file'] = 'Ошибка ' . $_FILES['file']['error'] . ' при загрузке файла. <a href="https://www.php.net/manual/ru/features.file-upload.errors.php" target="_blank">Код ошибки</a>';
        }
    };


  //  var_dump($_POST);



}
/*
var_dump($_POST);
echo "<pre>";
print_r ($stmt);
echo "</pre>";
*/
//print_r ($errors);
//print_r ($project_err);
//формирование запроса на добавление задачи

if ($errors == false && $date) {
    $user_id = $result_name_nick3[0];
    $add_task_sql = 'INSERT INTO task (`name`, `project_id`, `user`,`deadline`,`file`) VALUES (?, ?,?,?,?)';
    // делаем подготовленное выражение
    $stmt = db_get_prepare_stmt($con, $add_task_sql ,[
        $tsql_name,
   //     $tsql_project=>'project2',
     //   (int)$project_sq=>'project2',
        (int)$_POST['project2'],
        $user_id=>$userID,
        $date,
        $original_name
    ]);

    // исполняем подготовленное выражение
    mysqli_stmt_execute($stmt);

    header("Location: /");
/*
echo "<pre>";
print_r ($stmt);
echo "</pre>";
echo         $tsql_project."*2*";

var_dump($_POST);
*/

}
else{

}


/*
$user_id = $result_name_nick3[0];
//$tsql_project=[8];
$add_task_sql = 'INSERT INTO task (`name`, `project_id`, `user`,`deadline`) VALUES (?, ?,?,?)';
// делаем подготовленное выражение
$stmt = db_get_prepare_stmt($con, $add_task_sql ,[

$tsql_project,
$user_id,
$date
]);
var_dump( $tsql_project);


mysqli_stmt_execute($stmt);
var_dump($_POST);
*/














// Выполняем полученное выражение
//$result = mysqli_stmt_execute($stmt);











$title2="Дела в порядке ";
//$content2 = "";
//$name_user= "КОнстантин";
//$name_user= $result_name_nick3;
$user_task=[];






//вариант вывод ключей из массива $test,"title")
$page_content3= include_template ('../pages/form-task.php', [
    // вывод из простого mysqli_fetch_all 'type1'=> array_column ($test,"title"),
    'type_project'=> $task_sql2,
    //  'link_project'=>$task_sql_project_id,
    'task_c_name'=>$task_count1 ,
    //'task_c_name'=>$task_count_oll,
    //'task_c_name2'=>$task_count,
    'task_count_oll1' =>$task_count_oll ,
    'errors' => $errors,
    'show_complete_tasks'=> $show_complete_tasks
]);

$layout_content =include_template ('layout.php',
    ['content2'=>$page_content3,
        'title1'=> $title2,
        'name_user1' => $result_name_nick3
    ]);


//print ($page_content3 );
print ($layout_content );

//подсчет количества задач
function test_count ( $task_count_oll1 , $cat_task):int{
    $count = 0;
    foreach ($task_count_oll1  as $value) {
        if ($value ['title'] == $cat_task) {
            $count++;
        }
    }
    return $count;
};
//echo $test_count ."111";


// тестовая йункция подсчета оставвшегося времени
function date_diff3 ($date){
    $ts = time();
    $task_date_str =strtotime($date);
    $diff =  floor(($task_date_str-$ts)/3600);
    return $diff;
}
/*
$checker_get_params = 0;
foreach ($task_sql as $arr => $elem) {
    if($elem['id'] == $get_param_project_id){
        $checker_get_params++;
    };
};
*/

// Получаем массив задач, если есть get-параметр,
// то модифицируем запрос sql c условием, где project_id = get-параметру

















//список задач

$projects=[];


/* пример обработки ошибки
if (!$result) {
    $error = mysqli_error($con);
    print("Ошибка MySQL: " . $error);
}
*/

/* ошибка
$date_now = date_create('now');
$date_task = date_create($task['date_complete']);
$date_diff1 = date_diff($date_task,$date_now);
$date_diff2 = date_format('%a ');
*/
/*
function date_diff3 ($date){
    $datetime1 = date_create('now');
    $date2 = date_create($date);
    $interval = date_diff($datetime1, $date2);
    $interval->format('%a');
}
*/







?>