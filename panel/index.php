<?php 
// Here we require the config and database connection.
require("../configuration.php");
require("../connection.php");

require("check.php");

if($loginOK){

      if($_GET['page']){

            $page = $_GET['page'];
      }else{
            $page = "home";
      }
      
      require("./content/".$page.".php");

}else{

      require("./content/forbidden.php");

}
?>
<?php
// Desconectar o cerrar una session
if ($_SESSION['salir'] == 'SI') {
session_destroy(); 
header("Location: sing_in.php");
}

?>