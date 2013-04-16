<script type="text/javascript">
    $(function() {
        updateContain();
        $(window).resize(function(){
              updateContain();
        });        
    });
    function updateContain()
    {     
        var w = $("#content").width(),
        ww = $("#div-more-options").width();              
        if(ww>0)
        {
            $(".contain").css("width",w-ww-8);
        }    
        else 
        {
            $(".contain").css("width","85%");
        }  
        var h = $(".contain").height(),
            hh = $("#div-more-options").height();
            
        if(hh>h)
        {
            $(".contain").css("height",hh+1);
        }
        else 
        {
            $("#div-more-options").css("height",h+1); 
        }
        
    }
</script>
<div id="div-more-options" style="width: 180px; float:left;border-bottom: 1px solid #dadada;border-top:0" class="ui-widget-content">    
    <h3 class="ui-widget-header ui-state-default" style="height: 21px; text-align: center; padding-top: 5px;  border-left: 0; border-right: 0; font-size:11px;">MAS OPCIONES</h3>
    <ul id="list-more-options">
        <?php          
         foreach($rows as $k => $r)
         {
             $class="";
          if($r['controlador']==$_GET['controller'])
          {
              $class=" active-more-options ";              
              ?>
            <li class="<?php echo $class; ?>">
                <a  href="javascript:"><?php echo $r['descripcion']; ?></a>
            </li>
            <?php 
          }
          else {
              ?>
            <li class="<?php echo $class; ?>">
                <a  href="<?php echo $r['url'] ?>?controller=<?php echo $r['controlador'] ?>&action=<?php if($r['accion']==""){echo "index";}else{ echo $r['accion']; }?>"><?php echo $r['descripcion']; ?></a>
            </li>
            <?php 
          }
        
          }
        ?>
    </ul>
</div>