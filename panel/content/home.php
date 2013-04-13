<?php


require("../connection.php");
?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Panel</title>
	<style>
	article{

		margin: 0.45em 0.3em;
		padding:0.6em 0.4em;
	}
	article.waiting{
		background:#149;
		color:#eee;
	}
	article.answered{
		background:#333;
		color:white;
	}
	article.active{
		box-shadow: 0 0 50px 10px #149;
	}
	h3{
		display:inline-block;
		padding: 0;
		margin: 0;
	}
	abbr{
		text-align:right;
		float:right;
	}
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  	<script src="../timeago.js"></script>
  	<script>
		$(document).on("ready", iniciar);
		var lastId;
		lastId = 0;
		function loadLog(){        
		        $.post("../last_activity.php", {last_activity:1});                
	  	}
		function iniciar(){
	   		setInterval (loadLog, 3000);    
		};
	    </script>
	<script>

		function open_chat_popup(info) {
			var sessionId = info.currentTarget.id.substring(8);
			var id_article = info.currentTarget.id;
			var is_active = $("article#" + id_article).hasClass("active");
			if(is_active){
				var options = "toolbar=no, directories=no, status=no, menubar=no, scrollbar=no, resizable=no, width=300, height=450";
				var chat_url = "chat.php?sessionId="+sessionId;
				window.open(chat_url, 'Chat', options);
				console.log("article#" + id_article);
				$("article#" + id_article).removeClass("active");
			}else{
				console.log("This session is on chat now!");
			}
		}
		$(document).on("ready", inicio);
		function inicio(){
			$(".active").on("click", open_chat_popup);
		}
	</script>
</head>
<body>
	<h1>Welcome, <?php echo $UserNameL; ?></h1>

	<?php 
	if($UserRankL>0){

		echo "<h4>List of chat sessions</h4>";
		echo "<a href='javascript:void(0)' onclick='document.location.reload()'>Refresh</a>";

		$sql = "SELECT * FROM chat_sessions ORDER BY id DESC";
		$result = $db->query($sql);
function timeDiff($firstTime,$lastTime)
{

// convert to unix timestamps
$firstTime=strtotime($firstTime);
$lastTime=strtotime($lastTime);

// perform subtraction to get the difference (in seconds) between times
$timeDiff=$lastTime-$firstTime;

// return the difference
return $timeDiff;
}
		while($row = $result->fetch_assoc())
		{
			$result2 = $db->query("SELECT datetime FROM last_activity WHERE id_session = ".$row['id'].";");
			$row_datetime = $result2->fetch_assoc();
			$last_activity = $row_datetime['datetime'];
			$now_datetime = date('Y-m-d H:i:s');
			$mins = ($last_activity - $now_datetime) / 60;
			$difference = timeDiff($last_activity,$now_datetime);
			echo "last activity: ".$difference;

			if($row['status'] == 0){
				$statuskey = "waiting";
				if($difference>60){
					$result3 = $db->query("UPDATE  `chat_sessions` SET  `status` =  '3' WHERE  `chat_sessions`.`id` =".$row['id']." LIMIT 1");
					$row['status'] = 3;
				}
			}elseif($row['status'] == 1){
				$statuskey = "answered";
			}elseif($row['status'] == 3){
				$statuskey = "leaved";
			}
			$status = 'class="active '.$statuskey.'"'
			?>
			<article id="session-<?php echo $row['id'];?>" <?php echo $status; ?> >
				<h3><?php echo $row['query']." by ".$row['name'];?></h3> <small><?php echo "(".$row['email'].")"; ?>  - <b>status: <?php echo $statuskey;?></b></small>
				<abbr title="<?php echo date("c", strtotime($row['date']));?>"><?php echo date("d/m/Y H:i:s", strtotime($row['date']));?></abbr>
			</article>
			<?php
		}

	}
	?>
	<script>
			$("abbr").timeago();
	</script>
</body>
</html>