<script type="text/javascript">
    function editar()
    {
        if(Id!=""){window.location = "index.php?controller=<?php echo $name; ?>&action=edit&id="+Id;}
          else { alert("Seleccione algún Registro para Editarlo"); }          
    }
    function ver()
    {
        if(Id!=""){window.location = "index.php?controller=<?php echo $name; ?>&action=show&id="+Id;}
          else { alert("Seleccione algún Registro para Visualizarlo"); }          
    }
    function eliminar()
    {
       if(Id!=""){
                    if(confirm("Realmente deseas eliminar este registro"))
                    {
                            href = "index.php?controller=<?php echo $name; ?>&action=delete&id="+Id;
                            window.location = href;
                    }
                }
          else { alert("Seleccione algún Registro para Eliminarlo"); }
    }

    function msg_alerta(text)
    {
        $('#mensaje').removeClass();
        $('#mensaje').addClass('mensaje_alert');
        $('#mensaje').empty().append(text);
    }
</script>
<div class="option-op " >    
    <div class="operaciones" style="float:left; display: inline-block; margin-top: 3px;">      
        <?php if(mostrar($new)) { ?>
        <a class="nuevo" href="index.php?controller=<?php echo $name; ?>&action=create" title="Nuevo Registro">
            <span class="box-boton">Nuevo</span>
            <span class="box-boton"><img src="images/add.png"/></span>        
        </a>        
        <?php } 
        if(mostrar($edit)) { ?>
        <a class="editar" onclick="editar();" title="Editar Registro">
            <span class="box-boton">Editar</span>
            <span class="box-boton"><img src="images/table_edit.png"/></span> 
        </a>
        <?php } 
        if(mostrar($delete)) { ?>
        <a class="eliminar" onclick="eliminar();" title="Eliminar Registro" style="color:red;">
            <span class="box-boton">Eliminar</span>
            <span class="box-boton"><img src="images/delete.png"/></span> 
        </a>	        
        <?php } 
        if(mostrar($view)){ ?>
        <a class="ver" onclick="ver()" title="Ver Registro">
            <span class="box-boton">Ver</span>
            <span class="box-boton"><img src="images/zoom.png"/></span> 
        </a>	        
        <?php } ?>
        <!--<a href="index.php" style="color:red;">Cerrar</a>-->
    </div> 
    <?php if(!mostrar($select)){ ?>
    <div style="float: left; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="<?php echo $_SERVER['PHP_SELF']."?controller=".$name; ?>" style="border:0" title="Recargar Registros"><img alt="Refrescar" src="images/reload.png"/></a>
    </div>
    
    <div style="float: left; display: inline; width: 18px; height: 18px; margin: 3px 5px">
        <a href="index.php" style="border:0" title="Cerrar"><img alt="Cerrar" src="images/close.png"/></a>
    </div>
    <?php } ?>
    <div style="float: right; display: inline; margin-top: 3px">
        <?php echo $pag; ?>
    </div>
    <div style="clear: both"></div>
</div> 
<?php 
    function mostrar($op)
    {
        $res = false;
        if($op[0])
        {
            if($op[1])
            {
                $res = true;
            }            
        }
        return $res;
    }
?>
<!--    
    Autor: Andrés García Macedo (ADZ)    
-->