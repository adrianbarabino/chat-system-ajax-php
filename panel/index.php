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