<?php
include_once("Main.php");
class reportes extends Main
{    
    
    function data_cumpleanos($g)
    {
        $sql = "SELECT concat(e.idempleado,' - ',e.nombre,' ',e.apellidos) as empleado,
				        te.descripcion as tipo,
                                        o.descripcion as oficina,
                                        e.fecha_nacimiento
				FROM empleado as e inner join tipo_empleado as te on e.idtipo_empleado = te.idtipo_empleado
                                     inner join oficina as o on e.idoficina = o.idoficina
				WHERE month(e.fecha_nacimiento)=:mes
				ORDER by e.fecha_nacimiento";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':mes',$g,PDO::PARAM_STR);
        
    	$stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
    function data_fec_ven_rev($g)
    {
        $sql = "SELECT concat(propietario.idempleado,' - ',propietario.nombre,' ',propietario.apellidos) as propietario,
				        concat(v.marca,' - ',v.modelo,' - ',v.placa) as vehiculo,
				        v.fec_ven_rev as fecha
				FROM vehiculo as v inner join empleado as propietario on propietario.idempleado = v.idpropietario
				WHERE month(v.fec_ven_rev)=:mes and propietario.idtipo_empleado = 3
				ORDER by v.fec_ven_rev";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':mes',$g,PDO::PARAM_STR);
    	$stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
    function data_fec_ven_soat($g)
    {
        $sql = "SELECT concat(propietario.idempleado,' - ',propietario.nombre,' ',propietario.apellidos) as propietario,
				        concat(v.marca,' - ',v.modelo,' - ',v.placa) as vehiculo,
				        v.fec_ven_soat as fecha
				FROM vehiculo as v inner join empleado as propietario on propietario.idempleado = v.idpropietario
				WHERE month(v.fec_ven_soat)=:mes and propietario.idtipo_empleado = 3
				ORDER by v.fec_ven_soat";
		    $stmt = $this->db->prepare($sql);
		    $stmt->bindParam(':mes',$g,PDO::PARAM_STR);
    	  $stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
    function data_ingresos($g)
    {
        $sql = "SELECT cm.descripcion as concepto,        
				        concat(propietario.nombre,' ',propietario.apellidos) as recibi,
				        m.chofer,
				        m.placa,
				        m.fecha,
				        m.observacion,
				        md.cantidad*md.monto as total
				FROM movimiento as m inner join movimiento_detalle as md on m.idmovimiento = md.idmovimiento
				        inner join concepto_movimiento as cm on cm.idconcepto_movimiento = md.idconcepto_movimiento
				        left join empleado as propietario on propietario.idempleado = m.idpropietario and propietario.idtipo_empleado = 3
				WHERE m.tipo = 1 AND m.estado = 1 and m.fecha between :f1 and :f2
				ORDER by m.fecha";

        $fechai = $this->fdate($g['fechai'],'EN');
        $fechaf = $this->fdate($g['fechaf'],'EN');
		    $stmt = $this->db->prepare($sql);
		    $stmt->bindParam(':f1',$fechai,PDO::PARAM_STR);
        $stmt->bindParam(':f2',$fechaf,PDO::PARAM_STR);
    	  $stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
   function data_egresos($g)
   {
       $sql = "SELECT cm.descripcion as concepto, concat(p.ruc,'-',p.razonsocial) as proveedor, 
                      m.fecha, m.observacion, md.cantidad*md.monto as monto
               FROM movimiento as m inner join movimiento_detalle as md
                    on m.idmovimiento = md.idmovimiento inner join  proveedor as p 
                    on p.idproveedor = m.idproveedor inner join concepto_movimiento as cm
                    on cm.idconcepto_movimiento = md.idconcepto_movimiento 
               WHERE m.tipo = 2 and m.estado = 1 and m.fecha between :f1 and :f2
               ORDER BY m.fecha";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':f1',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':f2',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($r2);die;
       return array($r2);
   }
    function data_ventas($g)
   {
       $sql = "SELECT
				venta.fecha,
				venta.hora,
				tipo_documento.descripcion,
				venta.serie,
				venta.numero,
				pasajero.nombre,
				sum(venta_detalle.cantidad *  venta_detalle.precio) as total,
                                venta.idventa
			   FROM
				venta
				Inner Join venta_detalle ON venta.idventa = venta_detalle.idventa
				Inner Join pasajero ON pasajero.idpasajero = venta.idpasajero
				Inner Join tipo_documento ON tipo_documento.idtipo_documento = venta.idtipo_documento
				WHERE venta.estado=1 and venta.fecha between :p2 and :p3
				GROUP BY 
				venta.fecha,
				venta.hora,
				tipo_documento.descripcion,
				venta.serie,
				venta.numero,
				pasajero.nombre,
				venta.idventa";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($r2);die;
       return array($r2);
      }
   
      function data_envio($g)
      {
       $sql = "SELECT
				envio.fecha,
				envio.hora,
				concat(empleado.nombre ,' ', empleado.apellidos),
				vehiculo.marca,
				remitente.nombre,
				remitente.nombre,
				envio.numero
			  FROM
				envio
				Inner Join envio_detalle ON envio.idenvio = envio_detalle.idenvio
				Inner Join empleado ON empleado.idempleado = envio.idchofer
				Inner Join pasajero as remitente ON remitente.idpasajero = envio.idremitente
				Inner Join pasajero as consignado on consignado.idpasajero = envio.idconsignado
				Inner Join tipo_documento ON tipo_documento.idtipo_documento = envio.idtipo_documento
				Inner Join vehiculo ON vehiculo.idvehiculo = envio.idvehiculo
			  WHERE envio.fecha between :p2 and :p3 ";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($g['fechai']);die;
       return array($r2);
   }
    function data_salida($g)
   {
       $sql = "SELECT
				salida.fecha_pay,
				salida.hora_pay,
				concat(empleado.nombre,' ',empleado.apellidos) as nombre,
				vehiculo.marca,
				salida.numero
				FROM
				salida
				Inner Join empleado ON empleado.idempleado = salida.idempleado
				Inner Join vehiculo ON vehiculo.idvehiculo = salida.idvehiculo
			  WHERE salida.fecha_pay between :p2 and :p3 ";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($g['fechai']);die;
       return array($r2);
   }
   

}
?>