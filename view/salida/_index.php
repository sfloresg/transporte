<script type="text/javascript">
$(document).ready(function() {
	$(".anular").live('click',function(){
		if(Id!="")
		{
            if(confirm("Realmente deseas Anular este Envio"))
            {
                    href = "index.php?controller=salida&action=anular&id="+Id;                    
                    window.location = href;
            }
        }
          else { alert("Seleccione algún Registro para Anularlo"); }
	});
    $(".comp-t").live('click',function(){
        var $cel    =   $(this).parent(),
            $tr     =   $(this).parent().parent();
            i       =   $(this).parent().parent().find('td:eq(0)').html(),
            p       =   $cel.index();                        
        if(confirm("Estas seguro de Generar el Ticket ?"))
        {
            //$.get('index.php','controller=genn_doc&action=getcurrent&idtd=4',function(r){
            $.post('index.php','ctl=salida&act=payticket&i='+i,function(r)
            {
                if(r[0]=="1")
                {
                    //changehtml($cel,r[1]);                                        
                    $tr.find('td:eq('+(p-1)+')').empty().append(r[1]);
                    $tr.find('td:eq('+(p)+')').empty().append('<a class="lbooton" title="Imprimir Ticket">IMPRIMIR</a>');
                    //$tr.find('td:eq('+(p+1)+')').empty().append('<a class="lbooton warning anular" title="Imprimir Ticket">ANULAR</a>');
                    $tr.find('td:eq('+(p+1)+')').empty().append("<a style='color:green'>DISPONIBLE</a>");
                    $tr.find('td:eq('+(p+2)+')').empty().append("<a class='lbooton desp'>DESPACHAR</a>");
                }
                else 
                {
                    alert("A ocurrido un error en el servidor, porfavor actualize su pagina presionando la tecla F5 e intente de nuevo relizar este proceso."+r[1]);
                }
                
            },'json');
            //},'json');         
        }            
    });
    $('.desp').live('click',function(){
        var i = $(this).parent().parent().find('td:eq(0)').html(),
            $tr     =   $(this).parent().parent(),
            $cel    =   $(this).parent(),
            p = 6;

        if(confirm("Estas seguro de Despachar este Ticket ?"))
        {

            $.post('index.php','ctl=salida&act=despachar&i='+i,function(r)
            {
                
                if(r[0]=="1")
                {
                    //changehtml($cel,r[1]);

                    $tr.find('td:eq('+(p+1)+')').empty().append("<a style='color:green'>DESAPACHADO</a>");
                    $tr.find('td:eq('+(p+2)+')').empty();
                    //$tr.find('td:eq('+(p)+') a.anular').fadeOut();
                }
                else 
                {
                    alert("A ocurrido un error en el servidor, porfavor actualize su pagina presionando la tecla F5 e intente de nuevo relizar este proceso.");
                }
                
            },'json');
        }
    });
});
function changehtml(obj,str)
{
    obj.empty().append(str);        
}

</script>    
<div class="div_container">
<h6 class="ui-widget-header">GESTION DE INGRESOS Y SALIDAS DE VEHICULOS</h6>
<div id="addbotones">
	<a class="anular" href="javascript:" title="Anular Envio">
            <span class="box-boton">Anular</span>
            <span class="box-boton"><img src="images/delete.png"/></span>        
    </a>
</div>
<?php echo $grilla;?>
</div>