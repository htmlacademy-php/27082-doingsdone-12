<?php
///require_once ('helpers.php');
///
// $userID = $_SESSION['id'];
//$userName = $_SESSION['user'];
//$user = $_SESSION["user"];

// Если пользователь не вошёл в систему (т.е. нет о нем информации в сессии), подключаем тут же (!) страницу для гостя и выходим
session_start();
$user = $_SESSION["user"]["id"];
$userID=(int)$user;
if (!isset($_SESSION["user"]["id"])) {
header("location: /templates/guestf.php");
exit;}


//echo "<pre>";
//print_r ($user = $_SESSION["user"]);
//  echo "</pre>";
include ('helpers.php');
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

    $sort_project="SELECT * FROM task WHERE user= $userID ";
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
$projectuser1 = "SELECT * FROM project where user_id=$userID";
$taskuser ="SELECT name FROM task WHERE USER=$userID";
$name_nick="SELECT * FROM  users WHERE id=$userID";
// список задач с группами
//$task_usersql="SELECT * FROM project LEFT JOIN task on task.project_id=project.id where id_user=2 and project_id=$cat_task_id ";
//oll
$task_usersql_oll="SELECT * FROM project LEFT JOIN task on task.project_id=project.id where user_id=$userID ";
$result1_oll = mysqli_query($con, $task_usersql_oll);
$task_count_oll = mysqli_fetch_all($result1_oll, MYSQLI_ASSOC);
//echo "<pre>";
//print_r ($task_count_oll);
//echo "</pre>";
$result = mysqli_query($con, $projectuser);
//$result1 = mysqli_query($con, $task_usersql);

$result_name_nick = mysqli_query($con, $name_nick);
$sql_task_user= 'SELECT name FROM task WHERE `user`=$userID';
$result_sql_user= mysqli_query($con, $sql_task_user);


$task_sql2 = mysqli_fetch_all($result, MYSQLI_ASSOC);



$result_name_nick3 = array_column ((mysqli_fetch_all($result_name_nick, MYSQLI_ASSOC)),"name");



$title2="Дела в порядке ";

$user_task=[];


//вариант вывод ключей из массива $test,"title")
$page_content3= include_template ('main.php', [
   // вывод из простого mysqli_fetch_all 'type1'=> array_column ($test,"title"),
    'type_project'=> $task_sql2,
  //  'link_project'=>$task_sql_project_id,
      'task_c_name'=>$task_count1 ,
     //'task_c_name'=>$task_count_oll,
    //'task_c_name2'=>$task_count,
    'task_count_oll1' =>$task_count_oll ,
  //    print_r($task_count1),

  //  'get_id'=> ,
    'show_complete_tasks'=> $show_complete_tasks]);
$layout_content =include_template ('layout.php',
    ['content2'=>$page_content3,
        'title1'=> $title2,
        'name_user1' => $result_name_nick3
    ]);





print ($layout_content);

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


var_dump($_POST);






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
    $interval2=(int)$interval;
    return $interval2;
}
*/







?>
