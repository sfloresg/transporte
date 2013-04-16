<script type="text/javascript">
$(document).ready(function() {
	$(".anular").live('click',function(){
		if(Id!="")
		{
            if(confirm("Realmente deseas Anular este Envio"))
            {
                    href = "index.php?controller=envio&action=anular&id="+Id;                    
                    window.location = href;
            }
        }
          else { alert("Seleccione algún Registro para Anularlo"); }
	});
});
</script>    
<div class="div_container">
<h6 class="ui-widget-header">ENVIOS REGISTRADOS</h6>
<div id="addbotones">
	<a class="anular" href="javascript:" title="Anular Envio">
            <span class="box-boton">Anular</span>
            <span class="box-boton"><img src="images/delete.png"/></span>        
    </a>
</div>
<?php echo $grilla;?>
</div>
