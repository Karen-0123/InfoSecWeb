<?php

// 連接資料庫
$servername = "localhost";
$dbuser  = "root";
$dbpassword = "";
$dbname = "InfoSecWebDB";

$conn = new mysqli($servername, $dbuser , $dbpassword, $dbname);

if ($conn->connect_error) {
    die('資料庫連線錯誤:' . $conn->connect_error);
}
?>