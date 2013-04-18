<?php
    session_start();
    session_destroy();
    global $n;
    if(!isset($_GET['key'])) { $n=rand(1000,9999); } else { $n = base64_decode($_GET['key']); }    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>Sistema de Control de Transpores - Huallaga Express</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="expires" content="0" />
    <link type="text/css" href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/layout.css" rel="stylesheet" />
    <link href="css/cssmenu.css" rel="stylesheet" type="text/css" />    
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
</head>
    <script type="text/javascript">
        $(document).ready(function(){ 
            $("#usuario").focus();
            $("#frmlogin").submit(function(){
                var cv = $("#codival").val();                
                if(cv==CodVali)
                {
                   var user = $("#usuario").val();
                   if(user!="")
                   {
                        return true;
                   }
                   else {
                        alert("Ingrese el usuario.");
                        return false;
                        }
                 }
                 else {
                     alert("Codigo de verificacion incorrecto.");
                     return false;
                 }                 
            });
        });
    </script>
<body>
    <div id="menu-top">
        <div class="wrapper-login">
            <ul class="item-top">
                <li>
                    <b><a href="systramite.php"></a></b>
                </li> 
                <li>
                    <a href="#">Fecha: <?php echo date('d/m/Y') ?></a>
                </li>
                
            </ul>
        </div>
    </div>
    
    <div id="body" class="ui-corner-all">
        <div id="banner"></div>
        <div id="menu">
            
        </div>
        <div class="spacer"></div>   
        <?php 
            if(isset($_GET['error']))
            {
                echo '<div style="color:red; text-align:center; margin-top:20px">¡Vaya!, al parecer ha olvidado sus datos, intentelo de nuevo</div>';
            }
        ?>
        <div id="content" class="ui-corner-bottom" style="background: transparent; border: 0;">            
            <div style="color:#333; padding-top: 10px; font-size: 20px;text-align: center">ACCESO AL SISTEMA DE TRANSPORTES</div>
            <div class="div-login" style="width: 417px; padding: 5px;border:1px solid #dadada;  margin: 30px auto;">
                
             <div style="float:left; width: 115px;">
                 <img alt="" src="images/seguridad.png"></img>
             </div>
                
             <div style="float:left; width: 300px;border-left: 1px solid #dadada; padding-top: 15px; ">
                <form id="frmlogin" method="post"  action="process.php" >
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td align="right">Usuario </td>
                        <td colspan="2">:&nbsp;<input id="usuario" name="usuario" class="ui-widget-content ui-corner-all text" style=" width: 80%; text-align: left; " value=""  /></td>
                    </tr>
                    <tr>
                        <td align="right">Password </td>
                        <td colspan="2">:&nbsp;<input type="password" id="password" name="password" class="ui-widget-content ui-corner-all text" style=" width: 80%; text-align: left;" value=""/></td>
                    </tr>
                    <tr>
                        <td align="right">Verificacion</td>
                        <td width="90">:&nbsp;<input id="codival" name="codival" class="ui-widget-content ui-corner-all text" style=" width: 70%; text-align: left;" value="" /></td>
                        <td align="left"><img alt=""  src="../lib/capcha.php?key=<?php echo $n; ?>" width="60" height="20" border="0" style="float:left" /></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center">
                            <input type="submit" id="ingresar" value="Ingresar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="width: 90px; height: 23px;"   tabindex="3" />
                        </td>
                    </tr>
                </table>
        </form>
         </div>
             <div style="clear: both"></div>
        </div>

        <?php echo "<script>CodVali='".$n."';</script>"; ?>
        </div>
        <div  class="spacer"></div>
    </div>      
    <div id="dialog-session" title="Su sesión ha expirado." style="display:none">
        <div class="ui-state-error" style="padding: 0 .7em; border: 0">
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>Por favor vuelva a iniciar sesión.</strong></p>
        </div>
    </div>   
</body>
</html>
