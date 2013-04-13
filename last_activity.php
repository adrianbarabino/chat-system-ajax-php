<?php
	require("configuration.php");
	require("connection.php");


if($_POST['last_activity'] == 1){

	$result = $db->query("DELETE FROM `last_admin_activity`;");       
	if ($mysqli->affected_rows>0){
    	
	}
	else {
	    $result = $db->query("INSERT INTO  `last_admin_activity` (`id` ,`datetime`) VALUES (
	'1' ,  
	'".date('Y-m-d H:i:s')."' 
);");
	}


}
?>
