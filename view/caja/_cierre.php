<?php  include("../lib/helpers.php");  ?>
<script type="text/javascript">
    $(document).ready(function(){    
        $("#saldo").focus();
        $("#aperturar").live('click',function(){           
           var s = $("#saldo").val();
           $.post('index.php','controller=caja&action=cerrar&s='+s,function(d){
               if(d[0]==1)
                   {
                        $("#box-msg").hide("slow");
                        $("#box-apertura").empty();
                        $('#box-apertura').animate({
                                width: '500px',
                                marginTop:'20px',
                                height:'320px',
                                backgroundColor:"#FAFAFA",
                                fontSize:"14px"
                            }, 700, function() {
                                $("#box-apertura").append(d[1]);
                            });
                   }
               else 
               {
                   alert("A ocurrido un error, intentelo nuevamente.");
               }
           },'json');
        });
    })
</script>
<h6 class="ui-widget-header">CIERRE DE CAJA</h6>
<div id="box-apertura" style="padding: 30px; background:#FEF8B6; text-align: center; width: 800px; margin: 20px auto;">
    <label class="labels" style="width: 130px">TURNO: <?php echo $_SESSION['name_turno']; ?></label>        
    <label class="labels" style="width: 140px">FECHA : <?php echo $_SESSION['fecha_caja']; ?></label>
    <label class="labels" style="width: 90px">SALDO CAJA: </label>
    <input type="text" name="saldo" id="saldo" class="ui-widget-content ui-corner-all text" size="5" maxlength="8" onkeypress="return permite(event,'num')" />
    <br/>
    <a href="javascript:" id="aperturar" class="button">Cerrar Caja</a>    
</div>