<script type="text/javascript">
    $(document).ready(function(){        
 $("#fechai,#fechaf").datepicker({ dateFormat:'dd/mm/yy' });
$("#gen").click(function(){      
             if(valid())
                {
                    var str = $("#frm").serialize();
                    $.get('index.php','controller=reportes&action=html_envio&'+str,function(data){
                        $("#wcont").empty().append(data);
                    });
				}
        });
        $("#pdf").click(function(){
                 if(valid())
                {              
                    var str = $("#frm").serialize();
					window.open('index.php?controller=reportes&action=pdf_envio&'+str,"Reporte");
				}
        });
        
    });
	    function valid()
    {
        var bval = true;
            //bval = bval && $("#idarticulo").required();
            bval = bval && $("#fechai").required();
            bval = bval && $("#fechaf").required();
        return bval;
    }

</script>
<div class="div_container">
<h6 class="ui-widget-header">Reporte de Envios</h6>
    

<div style="padding: 20px; background: #EBECEC">
    <form name="frm" id="frm" action="" method="get">
        <!--<label class="labels" for="idarticulo">Articulo: </label>
        <input type="text" name="idarticulo" id="idarticulo" value="" class="ui-widget-content ui-corner-all text" size="8" />
        <input type="text" name="descripcion" id="descripcion" value="" class="ui-widget-content ui-corner-all text" size="45" />-->
        <label class="labels" for="periodoi">Fecha Inicial: </label>
        <input type="text" name="fechai" id="fechai" value="" class="ui-widget-content ui-corner-all text" size="8" />
        <label class="labels" for="periodof">Fecha Final: </label>
        <input type="text" name="fechaf" id="fechaf" value="" class="ui-widget-content ui-corner-all text" size="8" />

    </form>
    <div  style="clear: both; padding: 5px; width: auto;text-align: center">
        <a href="index.php" class="button">CERRAR</a>
        <a href="#" id="gen" class="button">GENERAR</a>
        <a href="#" id="pdf" class="button">VISTA PREVIA</a>
    </div>
</div>
<div id="wcont" style="padding: 10px;"></div>
</div>