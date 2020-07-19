<?php
$toid = $_GET['id'];
session_start();
$id = $_SESSION['id'];
?>
<style>
#stagec {
	display: none;
}
</style>
<script src="libs/socket.io.js"></script>
<script>
var socket = io('http://192.168.100.11:3000');
var zarr = {
	toid: <?php echo $toid; ?>,
	fromid: <?php echo $id; ?>,
	type: "video"
}
var zapros = JSON.stringify(zarr);
	socket.emit('begin call', zapros);

socket.on('cancel call', function(msg) {
	if (msg = <?php echo $id; ?>) {
		document.getElementById('stagec').style.display = "block";
		document.getElementById('stage1').style.display = "none";
	}
});

socket.on('accept call', function(msg) {;
	if (msg = <?php echo $id; ?>) {
		location="call.php?to=" + <?php echo $toid; ?> + "&init=1";
	}
});
</script>
<div id = "stage1">Соединение...</div>
<div id = "stagec">Вызов сброшен</div>