<?php
	require("configuration.php");
	require("./languages/".$config['lang'].".php");
	require("connection.php");
error_reporting("ALL");
if($_GET['step'] > 0){

if($_GET['step'] == 3){
if($_POST['mail']){

function run_query_batch($handle, $filename="")
{

global $db;
// --------------
// Open SQL file.
// --------------
if (! ($fd = fopen($filename, "r")) ) {
die("Failed to open $filename: " . $db->error() . "<br>");
}

// --------------------------------------
// Iterate through each line in the file.
// --------------------------------------
while (!feof($fd)) {

// -------------------------
// Read next line from file.
// -------------------------
$line = fgets($fd, 32768);
$stmt = "$stmt$line";

// -------------------------------------------------------------------
// Semicolon indicates end of statement, keep adding to the statement.
// until one is reached.
// -------------------------------------------------------------------
if (!preg_match("/;/", $stmt)) {
continue;
}

// ----------------------------------------------
// Remove semicolon and execute entire statement.
// ----------------------------------------------
$stmt = preg_replace("/;/", "", $stmt);

// ----------------------
// Execute the statement.
// ----------------------
if(!$result = $db->query($stmt, $handle)){
	die("Error in the query to the DB");
}

$stmt = "";
}

// ---------------
// Close SQL file.
// ---------------
fclose($fd);
}

run_query_batch($dbhandle, "chatsystem.sql");
$sql = "INSERT INTO users (name,password,rank,email) VALUES (";
			$sql .= "'".remove_tags($_POST["name"])."'";
			$sql .= ",'".md5(md5(remove_tags($_POST["pass"])))."'";
			$sql .= ",'2'"; // 2 is rank of admin
			$sql .= ",'".remove_tags($_POST["mail"])."'";
			$sql .= ")";
			$register_result = $db->query($sql); // Execute query
	?>
	
		<h1>Sucefully installed</h1>

		<p>Now you NEED delete this file (install.php) and you can now log-in in the panel (/panel/).
		</p>
	<?php
}
}

if($_GET['step'] == 1){
	?>
	<h1>Installation step 1</h1>
	<p>For continue you need provide an username and password for the Administrador/Operator</p>

	<form action="install.php?step=2" method="POST">
	
		<input type="text" id="name" name="name" placeholder="Name of the user" />
		<br>
		<input type="text" id="mail" name="mail" placeholder="Mail of the user" />
		<br>
		<input type="password" id="pass" name="pass" placeholder="Password" />
		<br>
		<button type="submit" value="install!">install</button>


	</form>
	<?php
}
}else{

	?>
	
	<h1>Welcome to the Chat System installation</h1>
	<p>
		This chat system is maked by Adrian Barabino (coding) and sk8walker (design), for learning and personal/comercial uses.
		<br>The code has been released with the Apache License 2.0, PLEASE read it.
		<br>Before to do click on "Lets go to install" you first need edit the file configuration.php!!
	</p>

	<h3>Installing</h3>
	<a href="install.php?step=1">Lets go to install</a>

 	<?php

}

?>