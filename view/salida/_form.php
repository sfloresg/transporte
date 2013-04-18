<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>
<script type="text/javascript" src="js/app/evt_form_salida.js" ></script>
<script type="text/javascript" src="js/validateradiobutton.js"></script>
<div class="div_container">
<h6 class="ui-widget-header">&nbsp;</h6>
<?php echo $more_options; ?>
<div class="contain" style="width: 812px; float: right; height:auto">
<?php header_form('INGRESOS / TICKETS'); ?>  
<form id="frm" action="index.php" method="POST">
    <input type="hidden" name="controller" value="salida" />
    <input type="hidden" name="action" value="save" />
    <div id="div-msg" class="div-msg ui-widget-overlay" style="display: none">
        <div id="div-text-msg" class="ui-dialog-content ui-widget-content">MENSAJE</div>
    </div>                
    <div class="contFrm">        
        <div class="contenido" >                            
            <h3 class="stitle">INGRESOS Y VENTA DE TICKETS</h3>    
            <fieldset class="ui-corner-all">
                <legend>Datos Basicos</legend>            
                <label for="idsalida" class="labels" style="width:130px">N°:</label>
                <input type="text" id="idsalida" name="idsalida" class="text ui-widget-content ui-corner-all" style=" text-align: center;" value="<?php if($obj->idsalida!=""){ echo str_pad($obj->idsalida,8,'0',0); }?>" readonly="" size="10"  />
                <br/>
                <label for="fecha" class="labels" style="width:130px">Fecha:</label>
                <input type="text" name="fecha" id="fecha" value="<?php if($obj->fecha!=""){echo fdate($obj->fecha,"ES");} else {echo date('d/m/Y');} ?>" class="ui-widget-content ui-corner-all text" size="10" style="text-align: center" title="Ingrese la Fecha" readonly="" />                                                
                <label for="hora" class="labels" style="width:60px">Hora:</label>
                <input type="text" name="hora" id="hora" value="<?php if($obj->hora!=""){echo $obj->hora;} else {echo date('H:i');} ?>" class="ui-widget-content ui-corner-all text" size="6" style="text-align: center" readonly="" />                                                
                <br/>
                <label for="idchofer" class="labels" style="width:130px">Chofer:</label>
                <?php echo $chofer; ?>
                <br/>
                <label for="idvehiculo" class="labels" style="width:130px">Vehiculo:</label>
                <?php echo $vehiculo; ?>                
                <?php 
                if($obj->numero!="")
                {
                ?>                
                <br/>
                <label for="nrooc" class="labels" style="width:130px">Numero:</label>
                <input type="hidden" name="serie" id="serie" value="<?php echo $obj->serie;?>" class="ui-widget-content ui-corner-all text" size="4" title="Serie del Documento" readonly="" />                                
                <input type="text" name="numero" id="numero" value="<?php echo $obj->numero;?>" class="ui-widget-content ui-corner-all text" size="10" title="Numero del Documento" onkeypress="" readonly="" disabled="" />
                <?php 
                }
                else {                
                    ?>
                <br/>           
                <label for="ticket" class="labels" style="width:130px">Generar Ticket:</label>
                <input type="checkbox" name="gticket" id="gticket" value="1" />
                <?php
                }
                ?>                
                <br/>
                <br/>
          </fieldset>          
          <div  style="clear: both; padding: 5px; width: auto;text-align: center">
            <?php if($_GET['action']!="show") { ?>
            <a href="#" id="save" class="button">GRABAR</a>
            <?php } 
            else {
                if($obj->estado=="1")
                {
                ?>
                <a href="index.php?controller=salida&action=anular&id=<?php echo $_GET['id']; ?>" class="button">ANULAR</a>
                <?php
            }
            }
            ?>
            <a href="index.php?controller=<?php echo $_GET['controller'] ?>" class="button">ATRAS</a>
          </div>
        </div>
    </div>
</form>
</div>
</div>