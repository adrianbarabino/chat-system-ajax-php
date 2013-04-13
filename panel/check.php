<?php 

// variables 
      $loginOK = false; 
      $UserIDL; 
      $UserNameL; 
      $UserEmailL; 
      $UserRankL; 
// comprobar que las cookies existan
if($_COOKIE["usEmail"] && $_COOKIE["usPass"]){
// sentencia SQL
$result = $db->query("SELECT * FROM users WHERE email='".$_COOKIE["usEmail"]."' AND password='".$_COOKIE["usPass"]."'");

if($row = $result->fetch_assoc())
{
// establecemos de nuevo las cookies
setcookie("usEmail",$_COOKIE["usEmail"],time()+7776000);
setcookie("usPass",$_COOKIE["usPass"],time()+7776000);
// establecemos la variable $loginOK a "true"
$loginOK = true;
$UserIDL = $row["id"];
$UserNameL = $row["name"];
$UserEmailL = $row["email"];
$UserRankL = $row["rank"];
}
else
{
//Destruimos las cookies.
setcookie("usEmail","x",time()-3600);
setcookie("usPass","x",time()-3600);

}
$result->free();
}
?>