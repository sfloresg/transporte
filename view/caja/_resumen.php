<?php
function turno($t)
{
    if($t==1)
    { return "NORMAL";}
    else { return "GUARDIANIA";}
}
?>
<style type="text/css">
    table tr td { background: #fff;}
</style>
<h2>REPORTE RESUMEN DE CIERRE DE CAJA</h2>
<h4>FECHA: <?php echo $fecha; ?>; TURNO: <?php echo turno($turno) ?></h4>
<h4>USUARIO: <?php echo $idusuario; ?></h4>
<table border="0" cellspacing="6" cellpadding="4" style="width: 300px; margin: 20px auto;">
    <tr style="font-size: 15px">
        <td align="left">Saldo Inicial:</td>        
        <td align="right" width="150"><?php echo $saldo_inicial; ?> S/.</td>
    </tr>
    <tr style="font-size: 15px">
        <td align="left">Saldo Declarado:</td>
        <td align="right"><?php echo $saldo_declarado; ?> S/.</td>
    </tr>
    <tr style="font-size: 15px">
        <td align="left">Saldo Real:</td>
        <td align="right"><?php echo $saldo_real; ?> S/.</td>
    </tr>
    <tr style="font-size: 15px">
        <td align="left">Diferencia:</td>
        <td align="right"><?php echo number_format(($saldo_declarado-$saldo_real),5) ?> S/.</td>
    </tr>
    <tr style="font-size: 15px">
        <td align="left">Saldo Neto:</td>
        <td align="right"><?php echo number_format($saldo_real+$saldo_inicial,5); ?> S/.</td>
    </tr>
</table>
<a href="index.php" style="display: block; width: 100%; text-align: center; margin-top: 10px;">Cerrar</a>