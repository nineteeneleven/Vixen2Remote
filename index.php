<?php
/*
*Writen by John Brechisci
*http://www.vixenlights.com/download/vixen_2(2)/plugins/add-ins/RemoveClientCommands.txt
*/


//Fill in the information for you server and client settings.
$VixenServer = "yourdomain.com:41402";

$ClientName = "yourClientName";

$NumChan = 64;


//No need to change anything below this line.
$debug = false;

ini_set('max_execution_time', 60);
?>

<html>
<head>
<title>Vixen Channel Control</title>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript">
function makewhite() {
  setTimeout(function() {
        $('#wrapper').css('border-color','red');
        makeOrange();
    }, 1000);

}
function makeOrange() {
  setTimeout(function() {
        $('#wrapper').css('border-color','green');
        makewhite();
    }, 1000);

}
makewhite();
</script>
<style type="text/css">
body{
	background-image: url(http://nineteeneleven.info/teal_blue_christmas_lights_texture_seamless.jpg);
	background-repeat: repeat;
}
#wrapper{
	width: 800px;
	height: 100%;
	margin-left: auto;
	margin-right: auto;
	background-color: white;
	border-radius: 30px;
	overflow: hidden;
	border: 3px solid green;
}

#menu{
margin-left: 10px;

}

#menu input[type="submit"]{
	margin-top: 30px;
	margin-bottom: 30px;
	width: 130px;
	height: 30px;
	border: 1px solid #333;
	border-radius: 15px;
	position: relative;
	display: inline-block;
	float: left;
	background-color: rgba(0,128,0,.6)
}

#menu input[type="submit"]:hover {
	cursor: pointer;
	background-color: rgba(225,0,0,.6);
}

button{
	width: 100px;
	height: 30px;
	border: 1px solid #333;
	border-radius: 15px;
	display: inline-block;
	margin: 5px;
	padding: 0px;
	float: left;
}

button:hover{
	cursor: pointer;
}

#response{
text-align: center;
font-size: 30px;

}

#error{
font-size: 50px;
font-weight: bolder;
color: red;
border-bottom: 3px solid red;
}

#seqBtn{
	margin-bottom: 20px;
	text-decoration: none;
	color: green;

}
#seqBtn:hover{
	color: red;
}

</style>
</head>
<body>
<div id='wrapper'>
	<center>
		<p> Vixen 2 Remote Control, by John Brechisci </p>
	</center>	
<div id='menu'>
	<form id='ListSeq' method='POST' action='index.php'>
		<input type='submit' value='List Sequences' form='ListSeq' name='listseq' />
	</form>

	<form id='play' method='POST' action='index.php'>
		<input type='submit' value='Play' form='play' name='play' />
	</form>

	<form id='pause' method='POST' action='index.php'>
		<input type='submit' value='Pause' form='pause' name='pause'/>
	</form>

	<form id='stop' method='POST' action='index.php'>
		<input type='submit' value='Stop' form='stop' name='stop' />
	</form>

	<form id='status' method='POST' action='index.php'>
		<input type='submit' value='Status' form='status' name='status'/>
	</form>

	<form id='toggle' method='POST' action='index.php'>
		<input type='submit' value='Toggle Channels' form='toggle' name='toggleChan'/>
	</form>

</div>


<?php

if($debug){
    $path=$ClientName . "/debug.html?action=";
    
}else{
    $path=$ClientName . "/command.html?action=";
 
}




if (isset($_POST["listseq"])) {

	$xml = @simplexml_load_file("http://" . $VixenServer . "/".$ClientName. "/command.html?action=list&type=sequence");

	if($debug){
		echo "</center>";
		echo "<pre>";
	 	print_r($xml);
	 	echo "</pre>";
	 	echo "<center>";
	}

		if (!empty($xml)) {
			$xmlResult = $xml->Sequences->Sequence;

			print("<div id='response'>");
			$i=1;


			foreach ($xmlResult as $sequence) {


				print("<a href='index.php?seq=".$sequence."' id='seqBtn'>". $sequence ."</a>");
				echo "<br />";
				$i++;
			}

			print("</div>");

			
		}else{
			print("<div id='error'>Cannot contact Server!</div>");
		}		
}

if (isset($_REQUEST["seq"])) {
	$xml = @simplexml_load_file("http://" . $VixenServer . "/".$ClientName. "/command.html?action=retrieve&scope=local&type=sequence&filename=" . $_REQUEST['seq']);
	if(!empty($xml)){
		print "<div id='response'>Downloaded " . $_REQUEST["seq"] . "</div>";
	}else{
		print "<div id='error'>Failed to retrieve sequence</div>";
	}

}




if (isset($_POST["play"])) {
	$xml = @simplexml_load_file("http://" . $VixenServer . "/" . $ClientName . "/command.html?action=execute");

		if (!empty($xml)) {
			print("<div id='response'>Play</div>");
		}else{
			print("<div id='error'>Cannot contact Server!</div>");
		}
}




if (isset($_POST["pause"])) {

	$xml = @simplexml_load_file("http://" . $VixenServer . "/".$ClientName. "/command.html?action=pause");

		if (!empty($xml)) {
			print("<div id='response'>Paused</div>");
		}else{
			print("<div id='error'>Cannot contact Server!</div>");
		}
}


if (isset($_POST["stop"])) {
	$xml = @simplexml_load_file("http://" . $VixenServer . "/".$ClientName. "/command.html?action=stop");

		if (!empty($xml)) {
			print("<div id='response'>Stopped</div>");
		}else{
			print("<div id='error'>Cannot contact Server!</div>");
		}
}




if (isset($_POST["status"])) {
	$xml = @simplexml_load_file("http://" . $VixenServer . "/".$ClientName. "/command.html?action=status");
	if($debug){
		echo "</center>";
		echo "<pre>";
	 	print_r($xml);
	 	echo "</pre>";
	 	echo "<center>";
	}
	$seqLen= $xml->Status->Program->attributes();
	$seqLen = $seqLen['length'];
	$seconds = substr($seqLen, 0,-3); //sequences are in 100ms so drop the last 3 digits
	$seqLen = gmdate("H:i:s", $seconds);
	echo "<div id='response'>";
	echo $xml->Status->Execution . "<br />";
	echo $seqLen . "<br />";
	echo $xml->Status->Sequence;
	echo "</div>";
	

}




if (isset($_POST["toggleChan"])) {

	$i = 1;
	while ( $i <= $NumChan) {
		print ("
		<button type='button' id='btn".$i."' onclick='ToggleChan".$i."()' style='background-color:red;'>Channel ".$i."</button>
		<div id='ch".$i."'></div>
		<script>
		function ToggleChan".$i."(){
			var link = '<iframe style=\"display:none;\" frameborder=\"0\"src=\"http://" . $VixenServer . "/" . $path . "toggle&channel=" . $i."\"></iframe>';

			document.getElementById('ch".$i."').innerHTML=link;

			var el = $('#btn".$i."');

			if(el.css('background-color') == 'rgb(0, 128, 0)'){
			    el.css('background-color','red');
			}else{
				el.css('background-color','green');
			}

		}
		</script>");


		$i++;
	}
}
?>
</div>
</body>
</html>
