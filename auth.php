<?php
session_start();
require "db.php";
$login = $_POST['login'];
$passwd = md5($_POST['passwd']);
$q = mysqli_query($db, "select * from users where login='$login' and passwd='$passwd';");
$str = mysqli_fetch_array($q);
if (!empty($str)) {
	$_SESSION['id'] = $str['id'];
	header('Location: my.php');
} else {
	header('Location: index.php?err=1');
}
?>