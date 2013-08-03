<?php
	require("configuration.php");
	require("./languages/".$config['lang'].".php");
	require("connection.php");

if($_POST['email']){
	global $phrase;
$mail = $config['contact_email'];

	$header = 'From: ' . $mail . " \r\n";
	$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
	$header .= "Mime-Version: 1.0 \r\n";
	$header .= "Content-Type: text/plain";
	//Estoy recibiendo el formulario, compongo el cuerpo 
   	$cuerpo .= $phrase["name"].": " . $_POST["name"] . "\n"; 
   	$cuerpo .= $phrase["email"].": " . $_POST["email"] . "\n"; 
   	$cuerpo .= $phrase["date"].": " . date('Y-m-d H:i:s') . "\n"; 
   	$cuerpo .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n"; 
   	$cuerpo .= $phrase["message"].": " . $_POST["query"] . "\n"; 

   	mail($mail,$phrase["form_subject_email"],$cuerpo, $header); 
   	echo $phrase["email_sent"];?>
   	<script language="javascript"> 
setTimeout("window.close();",5000) 
//--> 
</script>
<?php

}else{
	die("Error desconocido.");
}


?>