<?php
?><!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Admin Panel</title>
	<style>
	tr{

border:1px solid #aaa;
    box-shadow: 0px 0px 3px #ccc, 0 10px 15px #eee inset;
    border-radius:2px;
    color: #888;
    font-size: 12px;
    padding-right:30px;
    -moz-transition: padding .25s;
    -webkit-transition: padding .25s;
    -o-transition: padding .25s;
    transition: padding .25s;
	}
	tr.0{
	box-shadow: 0 0 5px #d45252;
    border-color: #b03535
	}
	tr.3{
		background:#555555;
		color:white;
	}
	tr.active{
	 box-shadow: 0 0 5px #5cd053;
    border-color: #28921f;
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
#tabla_home {min-height: 20px;
padding: 3px;
margin-bottom: 20px;
background-color: #f5f5f5;
border: 1px solid #e3e3e3;

border-radius: 4px;

box-shadow: inset 0 1px 10px rgba(0,0,0,0.05);
}
#tabla_home td {background-color: #fff;
border: 1px solid #ccc;

box-shadow: inset 0 1px 10px rgba(0,0,0,0.075);

transition: border linear .2s, box-shadow linear .2s;}
#tabla_home td.top {vertical-align:top;width:1%;text-align:right;}
#tabla_home thead tr {background:#414141;color:#fff}
#tabla_home thead tr td{background:#414141;border:2px solid #333;}
#tabla_home .r1 { background-color: #ffffff; }
#tabla_home .r2 { background-color: #dddddd; }
#tabla_home caption	{ font-style:italic; color:#999; }
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  	<script src="../timeago.js"></script>
  	<script src="../timeago-<?php echo $config['lang']; ?>.js"></script>
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
			var id_tr = info.currentTarget.id;
			var is_active = $("tr#" + id_tr).hasClass("active");
			if(is_active){
				var options = "toolbar=no, directories=no, status=no, menubar=no, scrollbar=no, resizable=no, width=300, height=450";
				var chat_url = "chat.php?sessionId="+sessionId;
				window.open(chat_url, 'Chat', options);
				console.log("tr#" + id_tr);
				$("tr#" + id_tr).removeClass("active");
			}else{
				console.log("This session is on chat now!");
			}
		}
		$(document).on("ready", inicio);
		function inicio(){
			setInterval(document.location.reload, 15000);
			$(".active").on("click", open_chat_popup);
		}
	</script>
</head>
<body bgcolor="#DDDDDD">
	<h1><?php echo $phrase['welcome']; ?>, <?php echo $UserNameL; ?></h2>


	<?php 
	if($UserRankL>0){
	?>



		<h4><?php echo $phrase['chat_sessions_list']; ?></h3>
		<a href='./process.php?action=logout'><?php echo $phrease['logaut']; ?></a>
 <table id="tabla_home" width="800" border="0" cellspacing="0" cellpadding="0" style="font-size: 16px" >
	<thead>
		
            <tr>
              <td>ID</td>
              <td><?php echo $phrase['name']; ?></td>
              <td><?php echo $phrase['message']; ?></td>
              <td><?php echo $phrase['email']; ?></td>
              <td><?php echo $phrase['status']; ?></td>
              <td><?php echo $phrase['query_time']; ?></td>
             </tr>  
   
	</thead>

	<tbody>
		
	<?php
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

			if($row['status'] == 0){
				$statuskey = $phrase['waiting'];
				if($difference>60){
					$result3 = $db->query("UPDATE  `chat_sessions` SET  `status` =  '3' WHERE  `chat_sessions`.`id` =".$row['id']." LIMIT 1");
					$row['status'] = 3;
				}
			}elseif($row['status'] == 1){
				$statuskey = $phrase['answered'];
			}elseif($row['status'] == 3){
				$statuskey = $phrase['unanswered'];
			}
			$status = 'class="active '.$row['status'].'"'


?>

            <tr id="session-<?php echo $row['id'];?>"
            	<?php echo $status; ?> >
			<td><?php echo $row['id'];?></td>
            <td><?php echo $row['name'];?> </td>
            <td>
                <?php echo $row['query'];?> </td>        
			<td><?php echo "(".$row['email'].")"; ?></td>  
			<td><?php echo $statuskey;?></td>
<td><abbr title="<?php echo date("c", strtotime($row['date']));?>"><?php echo date("d/m/Y H:i:s", strtotime($row['date']));?></abbr></td>
			</td>

</tr><?php
		}
	}

	?>
	<script>
			$("abbr").timeago();

			

</script>

</tbody>
</table>
</body>
</html>