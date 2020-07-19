<?php
session_start();
$id = $_SESSION['id'];
$toid = $_GET['to'];
if (empty($toid)) {
	$toid = 0;
}
$init = $_GET['init']
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Peer</title>
   <script src="libs/peerjs.min.js"></script>
   <script src="libs/socket.io.js"></script>
</head>
<body>
	<p><h3>Мой ID: </h3><span id=myid ></span></p>
	<input id=otherPeerId type=text placeholder="otherPeerId" > <button onclick="callToNode(document.getElementById('otherPeerId').value)">Вызов</button>
	
	<br>
	<video id=myVideo muted="muted" width="400px" height="auto" ></video>
	<div id=callinfo ></div>
	<video id=remVideo width="400px" height="auto" ></video>
<script>
var socket = io('http://192.168.100.11:3000');
var numb = "xvb";
// peerjs
var callOptions={'iceServers': [
		{url: 'stun:95.xxx.xx.x9:3479',		
		username: "user",
		credential: "xxxxxxxxxx"},
		{ url: "turn:95.xxx.xx.x9:3478",		
		username: "user",
		credential: "xxxxxxxx"}]
	}; 
peer= new Peer({host: '192.168.100.11', port: 9000, path: '/projv', config: callOptions});
peer.on('open', function(peerID) {
			numb = peerID
if (<?php echo $init; ?> == 1) {
	function connect() {
	var zarr = {
	toid: <?php echo $toid; ?>,
	fromid: <?php echo $id; ?>,
	serial: numb
}
var zapros = JSON.stringify(zarr);
socket.emit('connect call', zapros);
socket.on('connected', function(msg) {
	clearInterval(timer);
});
	}
let timer = setInterval(connect, 1000);
} else {
	socket.on('connect call', function(msg) {
	answ = JSON.parse(msg);
	console.log(msg);
	if (answ.toid == <?php echo $id; ?>) {
		callToNode(answ.serial);
		socket.emit('connected', <?php echo $id; ?>);
};
});
};
});
//end peerjs
//peerjs
var peercall;
peer.on('call', function(call) {
		  // Answer the call, providing our mediaStream
			peercall=call;
			callanswer()
		});
function callanswer() {
			navigator.mediaDevices.getUserMedia ({ audio: true, video: true }).then(function(mediaStream) {
		    var video = document.getElementById('myVideo');		    				  
		  peercall.answer(mediaStream); // отвечаем на звонок и передаем свой медиапоток собеседнику
		  //peercall.on ('close', onCallClose); //можно обработать закрытие-обрыв звонка
		  video.srcObject = mediaStream; //помещаем собственный медиапоток в объект видео (чтоб видеть себя)
		  document.getElementById('callinfo').innerHTML="Звонок начат... <button onclick='callclose()' >Завершить звонок</button>"; //информируем, что звонок начат, и выводим кнопку Завершить
		  video.onloadedmetadata = function(e) {//запускаем воспроизведение, когда объект загружен
			video.play();
		  };
		  setTimeout(function() {
                          //входящий стрим помещаем в объект видео для отображения
			  document.getElementById('remVideo').srcObject = peercall.remoteStream; 
			  document.getElementById('remVideo').onloadedmetadata= function(e) {
// и запускаем воспроизведение когда объект загружен
						document.getElementById('remVideo').play();
					  };
					  },1500);			  
				  
				  
			}).catch(function(err) { console.log(err.name + ": " + err.message); });
		}
function callToNode(peerId) { //вызов
		  navigator.mediaDevices.getUserMedia ({ audio: true, video: true }).then(function(mediaStream) {
		  var video = document.getElementById('myVideo');				  
		  peercall = peer.call(peerId,mediaStream); 
		  peercall.on('stream', function (stream) { //нам ответили, получим стрим
				  setTimeout(function() {
				  document.getElementById('remVideo').srcObject = peercall.remoteStream;
					  document.getElementById('remVideo').onloadedmetadata= function(e) {
						document.getElementById('remVideo').play();
					  };
					  },1500);	
				  });
				//  peercall.on('close', onCallClose);				  
				  video.srcObject = mediaStream;
				  video.onloadedmetadata = function(e) {
					video.play();
				  };
			}).catch(function(err) { console.log(err.name + ": " + err.message); });
		}
</script>
</body>