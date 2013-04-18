$(function() {        
    $("#idchofer,#idvehiculo").css("width","400px");
    $("#idchofer").focus();
    /*if($("#serie").val()=="")
    {
        load_sernum(4);   
    }   */ 
      
  
    $("#idchofer").change(function(){
       $("#idvehiculo").focus(); 
    });
    $("#idvehiculo").change(function(){
        $("#nrodocumentor").focus();
    });
    
    $( "#save" ).click(function(){
        bval = true;        
        bval = bval && $( "#idchofer" ).required();
        bval = bval && $( "#idvehiculo" ).required();
        if ( bval ) {
            $("#frm").submit();           
        }
        return false;
    });    
});
function load_sernum(idtd)
{   
    $("#idtipo_documento").val(idtd);
    $.get('index.php','controller=genn_doc&action=getcurrent&idtd='+idtd,function(r){        
        $("#serie").val(r.serie);
        $("#numero").val(r.numero);
    },'json');
    //$("#nrodocumento").focus();
}

function venter(evt)
{
    var keyPressed = (evt.which) ? evt.which : event.keyCode
    if (keyPressed==13)
    {
      
    }
}

function overlay()
{    
    var h = $("#div-more-options").height();       
    h = h - 5;    
    var os = $(".contain").offset();
    var w = $(".contain").width();
    w = w - 6;
    $("#div-msg").css({"left":os.left+"px","top":os.top+"px","width":w+"px","height":h+"px"});    
    $("#div-msg").fadeIn();
}
