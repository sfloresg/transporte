$(function() {
    $( "#idpadre" ).focus();
    $( "#idpadre" ).css({'width':'210px'});
    $("#div_activo").buttonset();
    $( "#save" ).click(function(){
        bval = true;        
        bval = bval && $( "#descripcion" ).required();        
        bval = bval && $( "#orden" ).required();
        bval = bval && $("#div_activo").validateradiobutton();
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