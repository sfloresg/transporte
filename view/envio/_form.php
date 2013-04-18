<?php  include("../lib/helpers.php"); 
       include("../view/header_form.php");
?>
<script type="text/javascript" src="js/app/evt_form_envio.js" ></script>
<script type="text/javascript" src="js/validateradiobutton.js"></script>
<div class="div_container">
<h6 class="ui-widget-header">&nbsp;</h6>
<?php echo $more_options; ?>
<div class="contain" style="width: 812px; float: right; height:auto">
<?php header_form('ENCOMIENDAS Y ENVIOS'); ?>  
<div class="box-msg"></div>
<form id="frm" action="index.php" method="POST">
    <input type="hidden" name="controller" value="envio" />
    <input type="hidden" name="action" value="save" />
    <div id="div-msg" class="div-msg ui-widget-overlay" style="display: none">
        <div id="div-text-msg" class="ui-dialog-content ui-widget-content">MENSAJE</div>
    </div>                
    <div class="contFrm">    
         <br/>
        <div class="contenido" >                            
            <h3 class="stitle">NOTA DE ENVIO</h3>    
            <fieldset class="ui-corner-all">
                <legend>Datos Basicos</legend>            
                <label for="idenvio" class="labels" style="width:130px">N°:</label>
                <input type="text" id="idenvio" name="idenvio" class="text ui-widget-content ui-corner-all" style=" text-align: center;width:60px" value="<?php if($obj->idenvio!=""){ echo str_pad($obj->idenvio,8,'0',0); }?>" readonly=""  />                
                <label for="fecha" class="labels" style="width:130px">Fecha:</label>
                <input type="text" name="fecha" id="fecha" value="<?php if($obj->fecha!=""){echo fdate($obj->fecha,"ES");} else {echo date('d/m/Y');} ?>" class="ui-widget-content ui-corner-all text" size="10" style="text-align: center" title="Ingrese la Fecha" readonly="" />                
                <br/>                
                <label for="nrooc" class="labels" style="width:130px">Serie-Numero:</label>
                <input type="text" name="serie" id="serie" value="<?php echo $obj->serie;?>" class="ui-widget-content ui-corner-all text" title="Serie del Documento" readonly="" style="width:40px" />
                <input type="text" name="numero" id="numero" value="<?php echo $obj->numero;?>" class="ui-widget-content ui-corner-all text"  title="Numero del Documento" onkeypress="" readonly="" style="width:60px" />
                <label for="iddestino" class="labels" style="width:80px">Destino:</label>
                <?php echo $destino; ?>            
                <br/>                
                <label for="idchofer" class="labels" style="width:130px">Chofer:</label>
                <?php echo $chofer; ?>
                <br/>
                <label for="idvehiculo" class="labels" style="width:130px">Vehiculo:</label>
                <?php echo $vehiculo; ?>
                <br/>                
                <label for="idremitente" class="labels" style="width:130px">Remitente:</label>
                <input type="hidden" name="idremitente" id="idremitente" value="<?php echo $obj->idremitente; ?>" />
                <input type="text" name="nrodocumentor" id="nrodocumentor" value="<?php echo $obj->nrodocumentor; ?>" class="ui-widget-content ui-corner-all text" size="13" title="Nro de Documento" onkeypress="return permite(event,'num')" />
                <input type="text" name="remitente" id="remitente" value="<?php echo $obj->remitente; ?>" class="ui-widget-content ui-corner-all text" title="Nombre del Remitente" style="width:300px" />
                <br/>
                <label for="idconsignado" class="labels" style="width:130px">Consignado a:</label>
                <input type="hidden" name="idconsignado" id="idconsignado" value="<?php echo $obj->idconsignado; ?>" />
                <input type="hidden" name="nrodocumentoc" id="nrodocumentoc" value="<?php echo $obj->nrodocumentoc; ?>" class="ui-widget-content ui-corner-all text" size="13" title="Nro de Documento" onkeypress="return permite(event,'num')" />
                <input type="text" name="consignado" id="consignado" value="<?php echo $obj->consignado; ?>" class="ui-widget-content ui-corner-all text" size="70" title="Nombre del Consignado" />
                <br/>
                <label for="direccion" class="labels" style="width: 130px;">Direccion: </label>
                <input type="text" name="direccion" id="direccion" value="<?php echo $obj->direccion; ?>" class="ui-widget-content ui-corner-all text" title="Direccion" style="width:410px;" />
                <!-- <a href="javascript:popup('index.php?controller=pasajero&action=search')">Search</a> -->
                <br/>       
          </fieldset>
          <?php if($_GET['action']!="show") { ?>
          <fieldset class="ui-corner-all" style="background: #fafafa;">
            <legend>Detalle de envio</legend>             
            <label for="descripcion" class="labels" style="width:100px">Descripcion:</label>
            <input type="text" name="descripcion" id="descripcion" value="" class="ui-widget-content ui-corner-all text" size="55" maxlength="200" />
            <label for="precio" class="labels" style="width:50px">Precio:</label>
            <input type="tex" name="precio" id="precio" value="5.00" class="ui-widget-content ui-corner-all text" size="4" onkeypress="return permite(event,'num')" />
            <label for="precio" class="labels" style="width:40px">Cant:</label>
            <input type="tex" name="cantidad" id="cantidad" value="1" class="ui-widget-content ui-corner-all text" maxlength="1" style="width:15px;" onkeypress="return permite(event,'num')" />                 
            <a href="javascript:" id="adddetalle"  class="button">
                Agregar 
            </a>            
          </fieldset>
          <?php } ?>
          <div id="div-detalle">
            <?php echo $detalle; ?>
          </div>
          <div  style="clear: both; padding: 5px; width: auto;text-align: center">
            <?php if($_GET['action']!="show") { ?>
            <a href="javascript:" id="save" class="button">GRABAR</a>
            <?php } 
            else {
                if($obj->estado=="1")
                {
                ?>
                <a href="index.php?controller=envio&action=anular&id=<?php echo $_GET['id']; ?>" class="button">ANULAR</a>
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