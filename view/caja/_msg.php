<script type="text/javascript">
    $(document).ready(function(){
        $("#si-ap").live('click',function(){
            $("#box-apertura").show("slow");
        });
        $("#aperturar").live('click',function(){
            var f = $("#fecha").val();
           $.post('index.php','controller=caja&action=aperturar&fecha='+f,function(d){            
             if(d[0]==1)
             {
                alert('Se ha aperturado la caja para el dia '+f);
                window.location = "index.php";
             }
             else {
              alert(d[1]);
             }
               
           },'json');
        });
    })
</script>
<h6 class="ui-widget-header">&nbsp;</h6>
<div style="text-align: center; width:500px; margin: 30px auto; display: block;">
    NO SE ENCONTRÓ NINGUNA CAJA APERTURADA EN EL TURNO, ¿DESEA APERTURAR CAJA EN ESTE MOMENTO?
    <br/><br/>
    <a href="javascript:" id="si-ap" class="links" >Si</a> <a href="index.php?controller=User&action=logout" class="links">No</a>
</div>
<div id="box-apertura" style="padding: 50px; background:#FEF8B6; display: none; text-align: center">
    <b>TURNO: <?php echo $_SESSION['name_turno']; ?><b/><label class="labels" style="width: 80px">Fecha :</label>
    <input type="text" name="fecha" id="fecha" value="<?php echo date('d/m/Y') ?>" size="10" class="ui-widget-content ui-corner-all text" readonly="true" />
    <a href="javascript:" id="aperturar" class="button">Aperturar</a>
</div>