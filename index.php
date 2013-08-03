<?php
// Si ingrrsarmos <? (forma corta de <?php) no lo toma tu programa de localhost

session_start();

require("configuration.php");
require("./languages/".$config['lang'].".php");
require("connection.php");
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
    $result2 = $db->query("SELECT datetime FROM last_admin_activity WHERE id='1';");
    $row_datetime = $result2->fetch_assoc();
    $last_activity = $row_datetime['datetime'];
    $now_datetime = date('Y-m-d H:i:s');
    $difference = timeDiff($last_activity,$now_datetime);
if(isset($_GET['logout'])){    

    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i>El usuario ". $_SESSION['name'] ." ha adandonado la sesion</i><br></div>");
    fclose($fp);
    
    session_destroy();
    header("Location: index.php"); 
}

function loginForm(){
    global $phrase;
    global $difference;
    if($difference>60){
echo'
    <div id="loginform"  class="offline" >
        <button id="openchat"title="button">'.$phrase["sales_chat_title_off"].'</button>
        <div id="yellow" style="display:none;">  
            <form action="email.php" id="chat" method="post" target="Chat">
                <div id="box">
                    <h2>'.$phrase["we_arent_online_now"].'</h2>
                    <h3>'.$phrase["contact_us_email"].'</h3>
                    <input type="text" placeholder="'.$phrase["name_placeholder"].'" name="name" id="name" /><br>
                    <input type="text" placeholder="'.$phrase["email_placeholder"].'" name="email" id="email" /><br>
                </div>
                <div id="msnzone">
                    <textarea size="140" placeholder="'.$phrase["enter_your_question"].'" name="query" id="query"></textarea><br>
                </div>
                    <input type="button" name="Ask" id="ask" value="'.$phrase["ask_button"].'"/>
            </form>
        </div>
    </div>

    </div>
    ';
    }else{
 echo'
    <div id="loginform" class="online" >
        <button id="openchat" title="button">'.$phrase["sales_chat_title"].'</button>
        <div id="yellow" style="display:none;">  
            <form action="chat.php" id="chat" method="post" target="Chat">
                <div id="box">
                    <h4>'.$phrase["enter_your_message"].'</h4>
                    <h3>'.$phrase["how_may_we_help_you"].'</h3>
                    <input type="text" placeholder="'.$phrase["name_placeholder"].'" name="name" id="name" /><br>
                    <input type="text" placeholder="'.$phrase["email_placeholder"].'" name="email" id="email" /><br>
                </div><div>Quedan <input type="text" name="remLen" size="1" maxlength="1" value="50" readonly> letras.</div>
                <div id="msnzone">
                    <form> 
<textarea name="query" id="query" wrap="physical" cols="20" placeholder="'.$phrase["enter_your_question"].'" rows="2" onkeydown="contador(this.form.query,this.form.remLen,50);" onkeyup="contador(this.form.query,this.form.remLen,50);" style="margin: 0px; width: 226px; height: 42px;"></textarea> 
</form>
                </div>
                    <input type="button" name="Ask" id="ask" value="'.$phrase["ask_button"].'"/>
            </form>
        </div>
    </div>

    </div>
    ';       
    }
    
}

if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Introduzca otro usuario</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>sk8walker chat</title>

    <link rel="stylesheet" href="style.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        function open_chat_popup() {
            var options = "toolbar=no, directories=no, status=no, menubar=no, scrollbar=no, resizable=no, width=373, height=520";
            var chat_url = "chat.php"
            window.open('', 'Chat', options);
            $("#chat").submit();
        }
        function open_chat_info(){
            $("#yellow").slideToggle(800, function () {
                var esta_visible = $("#yellow").is(":visible");
                if(esta_visible){
                    $("#openchat").addClass("abierto");
                }else{
                    $("#openchat").removeClass("abierto");

                }
                
            });
        }
        $(document).on("ready", inicio);
        function inicio(){
            $("#ask").on("click", open_chat_popup);
            $("#openchat").on("click", open_chat_info)
        }
    </script>
    <style>
    .offline #openchat{
        background: rgb(134, 30, 30);
    }
    .online #openchat{
        background: rgb(80, 134, 30);
    }

    </style>
<SCRIPT language="JavaScript" type="text/javascript"> 

function contador (campo, cuentacampo, limite) { 
if (campo.value.length > limite) campo.value = campo.value.substring(0, limite); 
else cuentacampo.value = limite - campo.value.length; 
} 

</script> 

</head>

<body>
mi pagina .com 
<?php
if(!isset($_SESSION['name'])){
    loginForm();
}
else{
?>


    <div id="wrapper">

    <div id="menu">
        <p class="welcome">Hola <b><?php echo $_SESSION['name']; ?></b></p>
        <p class="logout"><a id="exit" href="#">Salir</a></p>
        <div style="clear:both"></div>
    </div>  

    <div id="chatbox"><?php
    if(file_exists("log.html") && filesize("log.html") > 0){
        $handle = fopen("log.html", "r");
        $contents = fread($handle, filesize("log.html"));
        fclose($handle);
        
        echo $contents;
    }
    ?>
    </div>
    
    <form name="message" action="">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input name="submitmsg" type="submit"  id="submitmsg" value="Enviar" />
    </form>

</div>



<script type="text/javascript">
$(document).ready(function(){
    $("#submitmsg").click(function(){    
        var clientmsg = $("#usermsg").val();
        $.post("post.php", {text: clientmsg});                
        $("#usermsg").attr("value", "");
        return false;
    });
    
    function loadLog(){        
        var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
        $.ajax({
            url: "log.html",
            cache: false,
            success: function(html){        
                $("#chatbox").html(html);             
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                }                
              }
        });
    }
    setInterval (loadLog, 2500);    
    

    $("#exit").click(function(){
        var exit = confirm("¿Esta seguro de que quiere cerrar la sesión?");
        if(exit==true){window.location = 'index.php?logout=true';}        
    });
});
</script>

<?php
}
?>

</body>

</html>