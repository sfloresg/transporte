<?php include('../lib/helpers.php'); ?>
<div id="head-kardex" style="padding: 10px; ">
    <h2>REPORTE DE ENVIOS</h2>
    <br/>
</div>
<div class="contain" style="width:100%;  ">
<table class=" ui-widget ui-widget-content" style="width:100%" >
    <thead class="ui-widget ui-widget-content" >
        <tr class="ui-widget-header" style="height: 23px">
            <th >ITEM</th>
            <th >FECHA</th>
            <th >HORA</th>            
            <th >CHOFER</th>            
            <th >VEHICULO</th>
            <th >REMITENTE</th>
            <th >CONSIGNADO</th>            
            <th >NUMERO</th>
         </tr>
   </thead>
   <tbody>
       <?php         
       $i = 0;
       foreach($rowsi as $r)
       {
           $i += 1;
            ?>
            <tr>
                <td align="center"><?php echo str_pad($i, 3, '0', 0); ?></td>
                <td align="center"><?php  echo $r['fecha']; ?></td>
                <td align="center"><?php  echo $r['hora']; ?></td>
                <td align="center"><?php  echo $r[2]; ?></td>
                <td align="center"><?php echo $r[3]; ?></td>
                <td align="center"><?php echo $r[4]; ?></td>
                <td align="center"><?php echo $r[5]; ?></td>
                <td align="center"><?php echo $r[6]; ?></td>                
            </tr>
           <?php
       }
       
       
       ?>
   </tbody>
   
</table>      
<div style="clear: both"></div>    
</div>    