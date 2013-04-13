<?php 
// Here we require the config and database connection.
require("../configuration.php");
require("../connection.php");
//  We require the login.php form
require("login.php");

//--------------------------------------------

function quitar($mensaje)
{
$mensaje = str_replace("<","<",$mensaje);
$mensaje = str_replace(">",">",$mensaje);
$mensaje = str_replace("\'","'",$mensaje);
$mensaje = str_replace('\"','"',$mensaje);
$mensaje = str_replace('/\\\\/','/\/',$mensaje);
return $mensaje;
}

if($_POST["name"] != "" && $_POST["email"] != "")
{
$sql = "SELECT id FROM users WHERE email='".quitar($_POST["email"])."'";
$result = $db->query($sql); // We reeplace the old mysql php system for MySQLi
if($row = $result->fetch_assoc())
{
echo "Error, there are another account with this email";
}
else
{
$sql = "INSERT INTO users (name,password,rank,email) VALUES (";
$sql .= "'".quitar($_POST["name"])."'";
$sql .= ",'".md5(md5(quitar($_POST["password"])))."'";
$sql .= ",'1'"; // 1 is the rank of Normal User
$sql .= ",'".quitar($_POST["email"])."'";
$sql .= ")";
$register_result = $db->query($sql); // Execute query
echo "Sucefully Registered!";
}
}
else
{
echo "Debe llenar como minimo los campos de email y password";
}
$db->close();
?>