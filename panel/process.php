<?php 
// Here we require the config and database connection.
require("../configuration.php");
require("../connection.php");

$action = $_GET['action'];


if($action == "logout"){

	setcookie("usEmail","x",time()-3600);
	setcookie("usPass","x",time()-3600);
	?>
	Sucefully log-out, now you are redirected to the home.
	<SCRIPT LANGUAGE="javascript">
	location.href = "index.php";
	</SCRIPT>
	<?php

}elseif($action == "login"){

	if(trim($_POST["email"]) != "" && trim($_POST["password"]) != "")
	{
		$emailN = remove_tags($_POST["email"]);
		$passN = md5(md5(remove_tags($_POST["password"])));

		      
		$sql = "SELECT password FROM users WHERE email='$emailN'";
		$result = $db->query($sql); // We reeplace the old mysql php system for MySQLi
		if($row = $result->fetch_assoc())
		{
			if($row["password"] == $passN)
			{
				//90 dias dura la cookie
				setcookie("usEmail",$emailN,time()+7776000);
				setcookie("usPass",$passN,time()+7776000);
				?>
				Sucefully login, now you are redirected to the home!
				<SCRIPT LANGUAGE="javascript">
				location.href = "index.php";
				</SCRIPT>
				<?php
			}
			else
			{
				echo "Password incorrecto";
				echo "<input type='button' value='Volver Atras' onClick='history.go(-1);'>";
			}
		}
		else
		{
			echo "Usuario no existente en la base de datos";
			echo "<input type='button' value='Volver Atras' onClick='history.go(-1);'>";
		}
		// mysql_free_result($result);
	}
	else
	{
	echo "Debe especificar un email y password";
	echo "<input type='button' value='Volver Atras' onClick='history.go(-1);'>";
	}
	$db->close();


}elseif($action == "register"){

	if($_POST["name"] != "" && $_POST["email"] != "")
	{
		$sql = "SELECT id FROM users WHERE email='".remove_tags($_POST["email"])."'";
		$result = $db->query($sql); // We reeplace the old mysql php system for MySQLi
		if($row = $result->fetch_assoc())
		{
			echo "Error, there are another account with this email";
		}
		else
		{
			$sql = "INSERT INTO users (name,password,rank,email) VALUES (";
			$sql .= "'".remove_tags($_POST["name"])."'";
			$sql .= ",'".md5(md5(remove_tags($_POST["password"])))."'";
			$sql .= ",'1'"; // 1 is the rank of Normal User
			$sql .= ",'".remove_tags($_POST["email"])."'";
			$sql .= ")";
			$register_result = $db->query($sql); // Execute query
			echo "Sucefully Registered!";
			?>
			Sucefully registered, now you are redirected to the login form.
			<SCRIPT LANGUAGE="javascript">
			location.href = "login.php";
			</SCRIPT>
			<?php
		}
	}
	else
	{
		echo "Debe llenar como minimo los campos de email y password";
		echo "<input type='button' value='Volver Atras' onClick='history.go(-1);'>";
	}
	$db->close();


}else{

	die("Wrong parameters");
}


?>