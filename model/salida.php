<?php
include_once("Main.php");
class salida extends Main{    
    protected $tipo_documento = 4;    
    function index($query,$p,$c) 
    {
        $sql = "SELECT s.idsalida,
                       concat(substring(s.fecha,9,2),'/',substring(s.fecha,6,2),'/',substring(s.fecha,1,4)),
                       s.hora,
                       concat(chofer.nombre,' ',chofer.apellidos),
                       v.placa,    
                       s.numero,                   
                       case s.estado 
                       when 1 then 
                       concat('<a class=\"lbooton comp-t\" id=\"',s.idsalida,'\">COMPRAR</a>')
                       when 2 then 
                       concat('<a class=\"lbooton\" target=\"_blank\" href=\"index.php?controller=salida&action=printer&ie=',s.idsalida,'\" title=\"Imprimir\">IMPRIMIR</a>')
                       when 3 then 
                       concat('<a class=\"lbooton\" target=\"_blank\" href=\"index.php?controller=salida&action=printer&ie=',s.idsalida,'\" title=\"Imprimir\">IMPRIMIR</a>')
                       else '&nbsp;' end,                       
                       case s.estado when 0 then 'ANULADO'
                                     when 1 then 'INGRESADO'
                                     WHEN 2 THEN 'DISPONIBLE'
                                     WHEN 3 THEN 'DESPACHADO'
                            end as estado,
                        case s.estado when 2 then '<a class=\"lbooton desp\" >DESPACHAR</a>' 
                                      else '&nbsp;' end
                FROM salida as s inner join empleado as chofer on chofer.idempleado = s.idchofer
                        inner join vehiculo as v on v.idvehiculo = s.idvehiculo
                where ".$c." like :query and chofer.idtipo_empleado = 2
                order by s.idsalida desc";
        $param = array(array('key'=>':query' , 'value'=>"%$query%" , 'type'=>'STR' ));
        $data['total'] = $this->getTotal( $sql, $param );
        $data['rows'] =  $this->getRow($sql, $param , $p );        
        $data['rowspag'] =  $this->getRowPag($data['total'], $p );        
        return $data;
    }
    function edit($id)
    {
        $stmt = $this->db->prepare(" SELECT * 
                                    FROM salida
                                     WHERE idsalida = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetchObject();       
        //Retornando cabecera
        return $row;
    }
    function insert($_P ) 
    {
        $idperiodo  = $_SESSION['idperiodo'];
        $idempleado = $_SESSION['idempleado'];    
        $idoficina = $_SESSION['idoficina'];                
      
        $generar = null;
        if(isset($_P['gticket']))
        {
            $generar = 1;
            $monto = 7;
            $estado = 2;         
        }
        else {
            $generar = 0;
            $monto = 0;
            $estado = 1;
            $serie = '';
            $numero = '';
            
        }
        $hora = date('h:i:s');
        $stmt = $this->db->prepare("SELECT f_insert_salida (:p1,:p2,:p3,:p4,:p7,:p8,:p9,:p10,:p11,:p12,:p13); ");
        try 
        { 
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();                
            $stmt->bindParam(':p1',$this->tipo_documento,PDO::PARAM_INT);    
            $stmt->bindParam(':p2',$generar,PDO::PARAM_INT);  
            $stmt->bindParam(':p3',$_P['idchofer'],PDO::PARAM_STR);
            $stmt->bindParam(':p4',$_P['idvehiculo'],PDO::PARAM_INT);
            //$stmt->bindParam(':p5',$numero,PDO::PARAM_STR);
            //$stmt->bindParam(':p6',$serie,PDO::PARAM_STR);
            $stmt->bindParam(':p7',$this->fdate($_P['fecha'],'EN'),PDO::PARAM_STR);
            $stmt->bindParam(':p8',$hora,PDO::PARAM_STR);
            $stmt->bindParam(':p9',$monto,PDO::PARAM_INT);
            $stmt->bindParam(':p10',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':p11',$idempleado,PDO::PARAM_STR);
            $stmt->bindParam(':p12',$idoficina,PDO::PARAM_INT);
            $stmt->bindParam(':p13',$idperiodo,PDO::PARAM_INT);                      

            $stmt->execute();
            $row = $stmt->fetchAll();
            $idsalida = $row[0][0];
            $this->db->commit();            
            return array('res'=>"1",'msg'=>'Bien!','ids'=>$idsalida);
        }
        catch(PDOException $e) 
        {
            $this->db->rollBack();
            return array('res'=>"2",'msg'=>'Error : '.$e->getMessage() . $str);
        }
        
    }
    function anular($p) 
    {
        $stmt = $this->db->prepare("CALL anular_salida(:p1)");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    function payticket($id)
    {
        $idoficina = $_SESSION['idoficina'];
        $idperiodo  = $_SESSION['idperiodo'];
        $idempleado = $_SESSION['idempleado']; 
        $estado = 1;
        $hora = date('h:i:s');
        $fecha = date('Y-m-d');      
        $stmt = $this->db->prepare("SELECT f_pay_ticket (:p1,:p4,:p5,:p6,:p7,:p8,:p9); ");
        try 
        {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();
                $stmt->bindParam(':p1',$id,PDO::PARAM_INT);
                //$stmt->bindParam(':p2',$s,PDO::PARAM_STR);
                //$stmt->bindParam(':p3',$n,PDO::PARAM_STR);
                $stmt->bindParam(':p4',$this->tipo_documento,PDO::PARAM_INT);
                $stmt->bindParam(':p5',$idoficina,PDO::PARAM_INT);
                $stmt->bindParam(':p6',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':p7',$hora,PDO::PARAM_STR);
                $stmt->bindParam(':p8',$idperiodo,PDO::PARAM_INT);
                $stmt->bindParam(':p9',$idempleado,PDO::PARAM_INT);
                $stmt->execute();
                $ro = $stmt->fetchAll();
            $this->db->commit();
            return array('res'=>"1",'msg'=>$ro[0][0]);
        }
        catch(PDOException $e) 
        {
            $this->db->rollBack();
            return array('res'=>"2",'msg'=>'Error : '.$e->getMessage() . $str);
        }
    }
    function despachar($id)
    {
        
        $estado = 1;
        $hora = date('h:i:s');
        $fecha = date('Y-m-d');
      
        $stmt = $this->db->prepare("UPDATE salida set estado = 3, fecha_sal = :fs, hora_sal = :hs 
                                    where idsalida = :id ");
        try 
        { 
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();                
                $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                $stmt->bindParam(':fs',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':hs',$hora,PDO::PARAM_STR);                
                $stmt->execute();
                
            $this->db->commit();            
            return array('res'=>'1','msg'=>'Bien!');
        }
        catch(PDOException $e) 
        {
            $this->db->rollBack();
            return array('res'=>'2','msg'=>'Error : '.$e->getMessage() . $str);
        }
    }
    function getdata($idv)
    {
        $stmt = $this->db->prepare("select  p.nrodocumento as nrodoc,
                                            p.nombre as nombre,        
                                            v.serie as serie,
                                            v.numero as numero,
                                            v.fecha as fecha,
                                            v.guia_remision as guia_remision,
                                            v.idtipo_documento,
                                            p.direccion
                                    from salida as v inner join pasajero as p on p.idpasajero = v.idpasajero
                                    inner join tipo_documento as td on td.idtipo_documento = v.idtipo_documento
                                    where v.idsalida = :id;");
        $stmt->bindParam(':id',$idv,PDO::PARAM_INT);
        $stmt->execute();
        $n = $stmt->rowCount();
        if($n>0)
        {
            $head = $stmt->fetchObject();
            $type = "";
            if($head->idtipo_documento==1)
            {
                $type = "boleta";
            }
            else {
                $type = "factura";
            }
            $stmt = $this->db->prepare("select vd.cantidad,
                                               concat(d1.descripcion,' - ',d2.descripcion) as itinerario,
                                               vd.precio
                                        from salida_detalle as vd inner join itinerario as it on it.iditinerario = vd.iditinerario
                                                inner join destino as d1 on d1.iddestino = it.origen 
                                                inner join destino as d2 on d2.iddestino = it.destino
                                        where vd.idsalida = :id ");
            $stmt->bindParam(':id',$idv,PDO::PARAM_INT);
            $stmt->execute();
            $detalle = $stmt->fetchAll();
            return array(true,$head,$detalle,$type);
        }
        else 
        {
            return array(false,'','');
        }
    }
}
?>