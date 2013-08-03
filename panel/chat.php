<?php
	require("../configuration.php");
	require("../connection.php");
	require("../languages/".$config['lang'].".php");

require("check.php");

if($loginOK){

?><!doctype html>
	<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Admin Chat</title>
		<link rel="stylesheet" href="../style.css" />
		<style>
		#chatbox{
			padding: .8em;
		}
		#wrapper{
			border-radius:0px;
		}
		.timeago{
			float:right;
			font-size:8px;
			font-weight:bold;
		}
		.msgln{
			padding:0.2em;
			transition: all 0.3s ease-in-out;
			-webkit-transition: all 0.3s ease-in-out;
			-moz-transition: all 0.3s ease-in-out;
			-o-transition: all 0.3s ease-in-out;
			-ms-transition: all 0.3s ease-in-out;
		}
		.msgln:hover{
			background:#eee;
		}
		</style>
  	  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  	  <script src="../timeago.js"></script>
  	  <script src="../timeago-<?php echo $config['lang']; ?>.js"></script>
	</head>
	<body>
<?php

if($_GET['sessionId']){


$actual_session = $_GET['sessionId'];

$sql = "UPDATE  `chat_sessions` SET  `status` =  '1' WHERE `id` = '$actual_session' LIMIT 1";
$result = $db->query($sql); // Now the Session Status is 1 ! 

$sql = "SELECT *  FROM `chat_sessions` WHERE `id` ='$actual_session'";
$result = $db->query($sql); // We reeplace the old mysql php system for MySQLi
if($row = $result->fetch_assoc())
{
	$client_name = $row['name'];
	$client_email = $row['email'];
	$client_query = $row['query'];
}else{
	die("Error with the MySQL Database");
}

$user_id = $UserIDL;
$user_name = $UserNameL;
$phrase['welcome_message_operator'] = str_replace("%1", $client_name, $phrase['welcome_message_operator']);
$phrase['welcome_message_operator'] = str_replace("%2", $user_name, $phrase['welcome_message_operator']);
$welcome_message = $phrase['welcome_message_operator'];
if(!$result = $db->query("INSERT INTO  `chat_messages` (`id` ,`idsession` ,`iduser` ,`message` ,`name` ,`date`)
VALUES (
	NULL ,  
	'".$actual_session."',  
	'0',  
	'".$phrase['an_operator_joining']."',  
	'".$phrase['system_user']."',  
	'".date('Y-m-d H:i:s')."'  
);")){
    die('There was an error running the query [' . $db->error . ']');

}

if(!$result = $db->query("INSERT INTO  `chat_messages` (`id` ,`idsession` ,`iduser` ,`message` ,`name` ,`date`)
VALUES (
	NULL ,  
	'".$actual_session."',  
	'".$user_id."',  
	'".$welcome_message."',  
	'".$user_name."',  
	'".date('Y-m-d H:i:s')."'  
);")){
    die('There was an error running the query [' . $db->error . ']');

}



	?><script>console.log("<?php echo $actual_session; ?>");</script>
		

		<div id="wrapper">

		    <div id="menu">
		        <p class="welcome"><?php echo $phrase['hello_user']; ?> <b><?php echo $user_name; ?></b></p>
		        <p class="logout"><a id="exit" href="#"><?php echo $phrase['exit_user']; ?></a></p>
		        <div style="clear:both"></div>
		    </div>  

		    <div id="chatbox">
	    	</div>	
	    	<form name="message" action="">
		        <input name="usermsg" type="text" autofocus placeholder="<?php echo $phrase['enter_your_message']; ?>" id="usermsg" size="63" />
		        <input name="submitmsg" type="submit"  id="submitmsg" />
   			 </form>
		</div>
		<script>
		$(document).on("ready", iniciar);
		var lastId;
		lastId = 0;
		function loadLog(){        
		        var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		         $.ajax({
					type: "POST",
					url:"../json.php",
					data: {sessionId: '<?php echo $actual_session;?>', lastId: lastId},
					dataType:"json",
					async:false,
					success: function (data) {  
						var elementHTML;

						elementHTML = "";
						$.each(data, function(index, element){
							elementHTML += "<div class='msgln'> <abbr class='timeago' title='"+element.dateISO+"'>"+element.date+"</abbr> <b>"+ element.name+"</b>: "+element.message+".<br></div>";
							lastId = element.id;
						})
		                $("#chatbox").append(elementHTML);             
		                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		                $("abbr.timeago").timeago();
		                if(newscrollHeight > oldscrollHeight){
		                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
		                }      
		                $("#chatbox").animate({ scrollTop: $("#chatbox").prop("scrollHeight") }, 1500);
          
		              }
		        });
	  	}
		function iniciar(){
			$(".logout a").on("click", function(){
				window.close();
			})
			$("#submitmsg").click(function(){    
		        var clientmsg = $("#usermsg").val();
		        $.post("../post.php", {message: clientmsg, sessionId: '<?php echo $actual_session;?>', name: '<?php echo $user_name;?>', iduser: '<?php echo $user_id;?>'});                
		        $("#usermsg").val(' ');
		        return false;
		    });


	   		setInterval (loadLog, 3000);    
	   		$("abbr.timeago").timeago();
		};

	    </script>
	<?php

}else{
	?><h1>Welcome to our chat system</h2><?php
}

?>
	</body>
	</html>
<?php

}else{
	die("Error with the log-in, please log-in again!");
}

?>