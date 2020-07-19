<?php
$err = $_GET['err'];
if ($err == 1) {
	echo "Неверный логин или пароль!";
}
if ($err == 2) {
	echo "Войдите или зарегистрируйтесь для просмотра этой страницы.";
}
?>
<form action="auth.php" method="post">
	<input type="text" name="login" placeholder="Логин"><br>
	<input type="password" name="passwd" placeholder="Пароль"><br>
	<input type="submit" value="Вход">
 </form>