<script type="text/javascript">
    $(document).ready(function(){
        $("#gen").click(function(){      
            if(valid())
                {
                    var str = $("#frm").serialize();                    
                    $.get('index.php','controller=consultas&action=html_empleados&'+str,function(data){
                        $("#wcont").empty().append(data);
                    });
                }
        });
        $("#pdf").click(function(){
            if(valid())
                {
                    var str = $("#frm").serialize();
                    window.open('index.php?controller=consultas&action=pdf_empleados&'+str,"Reporte");
                }
        });
    });
    function valid()
    {
        var bval = true;     
            bval = bval && $("#idoficina").required();
        return bval;
    }
</script>
<div class="div_container">
<h6 class="ui-widget-header">Consulta de Empleados</h6>
<div style="padding: 20px; background: #EBECEC">
    <form name="frm" id="frm" action="" method="get">  
        <label for="idoficina" class="labels">Oficina: </label>
        <?php echo $oficinas; ?>
        <label for="estado" class="labels">Estado: </label>
         <select name="estado" id="estado">
            <option value="1">ACTIVOS</option>
            <option value="0">INACTIVOS</option>
         </select>
    </form>
    <div  style="clear: both; padding: 5px; width: auto;text-align: center">
        <a href="index.php" class="button">CERRAR</a>
        <a href="#" id="gen" class="button">GENERAR</a>
<!--        <a href="#" id="pdf" class="button">VISTA PREVIA</a>-->
    </div>
</div>
<div id="wcont" style="padding: 10px;"></div>
</div>