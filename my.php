<?php
session_start();
require "db.php";
$id = $_SESSION['id'];
$q = mysqli_query($db, "select * from users where id='$id';");
$str = mysqli_fetch_array($q);
if (empty($id)) {
	header('Location: index.php?err=2');
}
?>
<style>
#incall {
	display: none;
	background-color: darkgreen;
}
</style>
<h1>Добро пожаловать, <?php echo $str['login']; ?>!</h1>
<h2>Мои контакты:</h2>
<?php
$q2 = mysqli_query($db, "select * from contacts where inid='$id';");
while ($str2 = mysqli_fetch_array($q2)) {
	$fid = $str2['outid'];
	$q3 = mysqli_query($db, "select * from users where id='$fid';");
	$str3 = mysqli_fetch_array($q3);
	echo $str3['login'] . "<div id='online'></div><script>
	var t = '" . $str3['online'] . "'.split(/[- :]/);
	var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
	d = Date.parse(d);
	dd = Date.now();
	console.log(dd - d);
	if (dd - d < 200000) {
		document.getElementById('online').innerHTML = '<font color=\'green\'>В сети</font>';
	} else {
		document.getElementById('online').innerHTML = '<font color=\'red\'>Был" . $str3['pol'] . " в " . $str3['online'] . "</font>';
	}
	</script> ( <a href='callto.php?id=" . $str3['id'] . "'>Видеозвонок</a> ) <br>";
}
?>
<script src="libs/socket.io.js"></script>
<script>
var socket = io('http://192.168.100.11:3000');
var audio = new Audio();
audio.src = 'static/call.mp3';
audio.loop = true;

function iamonline() {
  socket.emit('online', <?php echo $id; ?>);
}
iamonline();
setInterval(iamonline, 180000);

var fromid = 0;
socket.on('query call', function(msg) {
	users = JSON.parse(msg);
	console.log(msg);
	if (users.toid == <?php echo $id; ?>) {
audio.play();
document.getElementById('incall').style.display = "block";
fromid = users.fromid;
}});

function acceptcall() {
	audio.pause();
	document.getElementById('incall').style.display = "none";
	socket.emit('accept call', fromid);
	location="call.php?init=0";
}

function closecall() {
	audio.pause();
	document.getElementById('incall').style.display = "none";
	socket.emit('cancel call', fromid);
}
</script>
<a href='logout.php'>Выход</a>
<div id = "incall"><p id = "incall-nick">username</p><p>Входящий </p><button onclick="acceptcall()">Принять</button><button onclick="closecall()">Отклонить</button></div>