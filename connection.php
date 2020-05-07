<?php
    $host="localhost";
	$user="pandomat_html";
	$password="pandomat19";
	$database="pandomat_test";
	

 
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
 
// выполняем операции с базой данных
$query ="SELECT * FROM wpjc_users";
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
if($result)
{
    echo "Выполнение запроса прошло успешно";
}
 
// закрываем подключение
mysqli_close($link);
?>