       $(document).ready(function() {
           posHContent();
           $(window).resize(function(){
              posHContent();
           });
           str = 'controller=config&action=get';
           $("#title-banner").css("display","none");
           $("#title-banner").show("slide",750);                 
           $.get('index.php','controller=index&action=Menu',function(menu){
                $("#menu").empty();                    
                var opciones_menu = menu;
                w = $(document).width();                
                $("#menu").generaMenu(opciones_menu);
            },'json');    
            
                 var $floatingbox = $('#menu-top');
 
                 if($('#body').length > 0){

                  var bodyY = parseInt($('#body').offset().top);
                  var originalX = $floatingbox.css('margin-left');

                  $(window).scroll(function () { 
                       
                   var scrollY = $(window).scrollTop();
                   var isfixed = $floatingbox.css('position') == 'fixed';

                   if($floatingbox.length > 0){
                      if ( scrollY > bodyY && !isfixed ) {                                
                                $floatingbox.stop().css({
                                  position: 'fixed',                                  
                                  marginLeft: 0,
                                  top:0
                                });
                        } else if ( scrollY < bodyY && isfixed ) {
                                  $floatingbox.css({
                                  position: 'absolute',
                                  top:0,
                                  marginLeft: originalX
                        });
                     }		
                   }
               });
             }
        });
//document.oncontextmenu = function(){ return false; }   
function posHContent()
{
  var h = $(document).height();
  $("#content").css({"min-height": h-160});
}