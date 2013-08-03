<?php
?><!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Admin Panel</title>
	<style>
	article{

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
	article.waiting{
	box-shadow: 0 0 5px #d45252;
    border-color: #b03535
	}
	article.answered{
		background:#555555;
		color:white;
	}
	article.active{
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
#table_exemple {min-height: 20px;
padding: 3px;
margin-bottom: 20px;
background-color: #f5f5f5;
border: 1px solid #e3e3e3;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);}
#table_exemple td {background-color: #fff;
border: 1px solid #ccc;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
-webkit-transition: border linear .2s, box-shadow linear .2s;
-moz-transition: border linear .2s, box-shadow linear .2s;
-o-transition: border linear .2s, box-shadow linear .2s;
transition: border linear .2s, box-shadow linear .2s;}
#table_exemple td.top {vertical-align:top;width:1%;text-align:right;}
#table_exemple th {background:#B7C5D4;text-align: center; color:#000}
#table_exemple .r1 { background-color: #ffffff; }
#table_exemple .r2 { background-color: #dddddd; }
#table_exemple caption	{ font-style:italic; color:#999; }
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
<body bgcolor="#DDDDDD">
	<h1>Bienvenid@, <?php echo $UserNameL; ?></h2>

 <table id="table_exemple" width="800" border="0" cellspacing="0" cellpadding="0" style="font-size: 11pt" >
            <tr>
              <th width="70">Nombre</th>
              <th width="130">Mensaje</th>
              <th width="100">Email</th>
              <th width="50">Estado</th>
              <th width="130">Hora de consulta</th>
              <th width="130">ID</th>
              <th width="20">Accion</th>
             </tr>  
   
	<?php 
	if($UserRankL>0){

		echo "<h4>Lista de las sesiones de chat</h3>";
		echo "<a href='javascript:void(0)' onclick='document.location.reload()'>Actualizar </a>";


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
			echo "última actividad: ".$difference;

			if($row['status'] == 0){
				$statuskey = "Hablando..";
				if($difference>60){
					$result3 = $db->query("UPDATE  `chat_sessions` SET  `status` =  '3' WHERE  `chat_sessions`.`id` =".$row['id']." LIMIT 1");
					$row['status'] = 3;
				}
			}elseif($row['status'] == 1){
				$statuskey = "Atendído";
			}elseif($row['status'] == 3){
				$statuskey = "Hojas";
			}
			$status = 'class="active '.$statuskey.'"'


?>

            <tr>
            <td><?php echo $row['name'];?> </td>
            <td><article id="session-<?php echo $row['id'];?>"
            	<?php echo $status; ?> >
                <?php echo $row['query'];?> </td>        
			<td><?php echo "(".$row['email'].")"; ?></td>  
			<td><?php echo $statuskey;?></b></td>
<td><abbr title="<?php echo date("c", strtotime($row['date']));?>"><?php echo date("d/m/Y H:i:s", strtotime($row['date']));?></abbr></td>
			</article></td>
			<td><?php
		}
	}

	?>
	<script>
			$("abbr").timeago();

</script>
</td>
<td>
Borrar	
</td>
</tr>
</table>
</body>
</html>