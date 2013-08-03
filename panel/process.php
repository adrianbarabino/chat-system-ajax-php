<?php 
// Here we require the config and database connection.
require("../configuration.php");
require("../connection.php");
require("../languages/".$config['lang'].".php");

$action = $_GET['action'];



if($action == "logout"){

	setcookie("usEmail","x",time()-3600);
	setcookie("usPass","x",time()-3600);
	echo $phrase['sucefully_log_out_now_you_be_redirected']
	?>
	<SCRIPT LANGUAGE="javascript">
	location.href = "index.php";
	</SCRIPT>
	<?

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
				echo $phrase['sucefully_log_in_now_you_be_redirected']
				?>
				
				<SCRIPT LANGUAGE="javascript">
				location.href = "index.php";
				</SCRIPT>
				<?
			}
			else
			{
				echo $phrase['wrong_password'];
			}
		}
		else
		{
			echo $phrase['user_doesnt_exist_in_the_database'];
		}
		// mysql_free_result($result);
	}
	else
	{
	echo $phrase['you_must_fill_fields'];
	}
	$db->close();


}elseif($action == "register"){

	if($_POST["name"] != "" && $_POST["email"] != "")
	{
		$sql = "SELECT id FROM users WHERE email='".remove_tags($_POST["email"])."'";
		$result = $db->query($sql); // We reeplace the old mysql php system for MySQLi
		if($row = $result->fetch_assoc())
		{
			// echo "Error, there are another account with this email";
			echo $phrase['error_duplicated_email'];
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
			

			// Sucefully registered, now you are redirected to the login form.
			
			echo $phrase['sucefully_registered_now_you_be_redirected'];
			?>
			<SCRIPT LANGUAGE="javascript">
			location.href = "login.php";
			</SCRIPT>
			<?
		}
	}
	else
	{
		// "Debe llenar como minimo los campos de email y password"
		echo $phrase['you_must_fill_fields'];
	}
	$db->close();


}else{

	die($phrase['wrong_parameters']);
}


?>