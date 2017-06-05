<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/5
 * Time: 19:42
 */
$pdo = new PDO('mysql:host=localhost;dbname=hm4;charset=utf8','root','123456');
$type = $_POST['type'];
$data = $_POST['data'];
$sql = "select from user where $type = '$data'";

$res = $pdo->query($sql);
$row = $pdo->fetch(PDO::FETCH_ASSOC);

echo "<table border='1' width='400'>";
echo "<tr><td>no</td><td>".$row['id'."</td></tr>";
echo "</table>";