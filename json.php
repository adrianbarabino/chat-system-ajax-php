<?php
	require("configuration.php");
	require("connection.php");

	if($_REQUEST['sessionId'] || $_REQUEST['sessionId']){
		if($_REQUEST['sessionId']){
			$sessionId = $_REQUEST['sessionId'];
		}elseif ($_REQUEST['sessionId']) {
			$sessionId = $_REQUEST['sessionId'];
		}else{
			die("Wrong request!");
		}
		$sql = "SELECT * FROM  `chat_messages` WHERE  `idsession` = ".$sessionId;
		if($_REQUEST['lastId'] || $_REQUEST['lastId']){
		if($_REQUEST['lastId']){
			$lastId = $_REQUEST['lastId'];
		}elseif ($_REQUEST['lastId']) {
			$lastId = $_REQUEST['lastId'];
		}
			$sql = $sql." AND `id` > ".$lastId;

		}
		$sql = $sql." LIMIT 0,999";

		if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
		}
		?>[
<?php
		while($row = $result->fetch_assoc()){
$message = trim(preg_replace('/\s+/', ' ', $row["message"]));

$response .= '{'. "\n";
$response .= '    "id": '.$row["id"].', '. "\n";
$response .= '    "iduser": '.$row["iduser"].', '. "\n";
$response .= '    "name": "'.$row["name"].'", '. "\n";
$response .= '    "dateISO": "'.date("c",strtotime($row["date"])).'", '. "\n";
$response .= '    "date": "'.date("g:i A",strtotime($row["date"])).'", '. "\n";
$response .= '    "message": "'.remove_tags($message).'"'."\n";
$response .= '},'. "\n";

		}
		echo substr($response, 0, -2);
		?>

]
<?php


if($_REQUEST['last_activity'] == 1){

	$result = $db->query("DELETE FROM `last_activity` WHERE id_session='".$sessionId."';");       
	if ($mysqli->affected_rows>0){
    	
	}
	else {
	    $result = $db->query("INSERT INTO  `last_activity` (`id` ,`id_session`,`datetime`) VALUES (
	NULL ,  
	'".$sessionId."',  
	'".date('Y-m-d H:i:s')."' 
);");
	}


}
	}
?>