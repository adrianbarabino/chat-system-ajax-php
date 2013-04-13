<?php

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
if($db->connect_errno > 0){
	die('Error in database conection [' . $db->connect_error .']');
}


?>