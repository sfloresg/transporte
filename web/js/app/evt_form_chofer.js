$(function() {
    $("#div_activo").buttonset();
    $("#fecha_registro,#fecha_nacimiento").datepicker({dateFormat:'dd/mm/yy'});
    $("#idoficina","#idgrupo").css("width","auto");
    $( "#save" ).click(function(){
        bval = true;
        bval = bval && $( "#idusuario" ).required();
        //bval = bval && $( "#idperfil" ).required();
        //bval = bval && $( "#idtipo_empleado" ).required();
        bval = bval && $( "#idoficina" ).required();
        bval = bval && $( "#idgrupo" ).required();
        bval = bval && $( "#nombre" ).required();
        bval = bval && $( "#apellidos" ).required();        
        
        if ( bval ) {
            $("#frm").submit();
        }
        return false;
    });

    $( "#delete" ).click(function(){
          if(confirm("Confirmar Eliminacion de Registro"))
              {
                  $("#frm").submit();
              }
    });
});