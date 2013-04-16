<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>SISTEMA DE CONTROL DE TRANSPORTE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="expires" content="0" />
    <link type="text/css" href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/layout.css" rel="stylesheet" />
    <link href="css/cssmenu.css" rel="stylesheet" type="text/css" />
    <link href="css/style_forms.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>    
    <script type="text/javascript" src="js/menus.js"></script>
    <script type="text/javascript" src="js/session.js"></script>
    <script type="text/javascript" src="js/required.js"></script>
    <script type="text/javascript" src="js/validateradiobutton.js"></script>
    <script type="text/javascript" src="js/utiles.js"></script>
    <script type="text/javascript" src="js/js-layout.js"></script>
</head>
<body>
    <div id="menu-top">
        <div class="wrapper-login">
            <ul class="item-top">
                <li>
                    <b>EMPRESA SAN MARTIN S.A.</b>
                </li>
                <li>
                    PERIODO: <?php echo $_SESSION['name_periodo']; ?>
                </li>
                <li>
                    <?php echo $_SESSION['oficina'] ?>
                </li>               
                <li>
                    Fecha: <?php echo $_SESSION['fecha_caja'] ?>
                </li>
                <li>
                    TURNO: <?php echo $_SESSION['name_turno'] ?>
                </li>
            </ul>            
            <!-- <div id="wrapp-search">
            <input type="text" name="txtbuscar" id="txtbuscar" class="ui-widget-content text" style="width:150px; text-align:center; padding:2px; font-weight:bold; font-size:12px; margin:0" maxlength="8" placeholder="Buscar Expediente ..." />
            <a href="#">Busqueda Avanzada</a>
            </div> -->
            <div style="float:right">                
                <a href="#" class="text-login"><?php echo strtoupper($_SESSION['name']); ?></a>
                <a href="index.php?controller=user&action=logout" class="text-login">Cerrar Session</a>                
            </div>
        </div>
    </div>    
    <div id="body">
         <div id="banner">
             <img id="img-logo" src="images/auto-logo.png"  style="float: left; margin-top: 10px; margin-left: 10px; display: block; " width="120"/>
             <div id="title-banner">
                 <b>SISTEMA ADMINISTRATIVO DE TRANSPORTE</b>
                 <p style="font-size: 10px;">EMPRESA SAN MARTIN S.A.</p>
             </div>
         </div>
        <div id="menu"></div>
        <div class="spacer"></div>        
        <div id="content">
            <?php echo $content; ?>
        </div>
        <div  class="spacer"></div>
        <div id="foot" class="ui-corner-bottom ui-widget-header">
            A.J.-2012
        </div>
        <div  class="spacer"></div>        
    </div>
    <div id="dialog-session" title="Su sesión ha expirado." style="display:none">
        <div class="ui-state-error" style="padding: 0 .7em; border: 0">
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>Por favor vuelva a iniciar sesión.</strong></p>
        </div>
    </div>
    <div id="dialog"></div>
</body>
</html>