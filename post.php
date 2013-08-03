
<?php
	require("configuration.php");
	require("connection.php");

if($_POST['sessionId']){
	$actual_session = $_POST['sessionId'];
	$name = $_POST['name'];
	$iduser = $_POST['iduser'];
	$message = $_POST['message'];

if(!$result = $db->query("INSERT INTO  `chat_messages` (`id` ,`idsession` ,`iduser` ,`message` ,`name` ,`date`)
VALUES (
	NULL ,  
	'".$actual_session."',  
	'".$iduser."',  
	'".$message."',  
	'".$name."',  
	'".date('Y-m-d H:i:s')."' 
);")){
    die('There was an error running the query [' . $db->error . ']');

}

}else{
	die("Wrong Request!");
}
?>