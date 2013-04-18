<?php
include_once("Main.php");
class envio extends Main{    
    protected $tipo_documento = 3;    
    function index($query,$p,$c) 
    {
        $sql = "select e.idenvio,
                       concat(substring(e.fecha,9,2),'/',substring(e.fecha,6,2),'/',substring(e.fecha,1,4)),
                       remitente.nombre,
                       e.consignado,
                       e.numero,
                       case e.estado when 1 then 'ENVIADA'
                                     when 2 then 'RECIBIDA'
                                     WHEN 0 THEN 'ANULADA'
                            end as estado,
                        case e.estado when 1 then 
                       concat('<a target=\"_blank\" href=\"index.php?controller=envio&action=printer&iv=',e.idenvio,'\" title=\"Imprimir\"><img src=\"images/print.png\" /></a>')
                       else '&nbsp;' end
                from envio as e inner join pasajero as remitente on remitente.idpasajero = e.idremitente                  
                where ".$c." like :query
                order by e.idenvio desc ";
        $param = array(array('key'=>':query' , 'value'=>"%$query%" , 'type'=>'STR' ));
        $data['total'] = $this->getTotal( $sql, $param );
        $data['rows'] =  $this->getRow($sql, $param , $p );        
        $data['rowspag'] =  $this->getRowPag($data['total'], $p );        
        unset($_SESSION['envios']);
        return $data;
    }
    function edit($id)
    {
        $stmt = $this->db->prepare("SELECT e.*,
                                        remitente.nrodocumento as nrodocumentor,
                                        remitente.nombre as remitente,                                        
                                        e.consignado as consignado
                                     FROM envio as e 
                                      inner join pasajero as remitente on remitente.idpasajero = e.idremitente 
                                     WHERE e.idenvio = :id");
        
        $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetchObject();

        //Obteniendo los detalles
        $stmt = $this->db->prepare("SELECT  descripcion,
                                            precio,
                                            cantidad
                                    FROM envio_detalle 
                                    WHERE idenvio = :id and estado = 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        unset($_SESSION['envios']);
        $_SESSION['envios'] = new envios();
        foreach($stmt->fetchAll() as $rows)
        {
            $_SESSION['envios']->add($rows['descripcion'],$rows['precio'],$rows['cantidad']);
        }

        //Retornando cabecera
        return $row;
    }
    function insert($_P ) 
    {
        $idperiodo  = $_SESSION['idperiodo'];
        $idempleado = $_SESSION['idempleado'];    
        $idoficina = $_SESSION['idoficina'];
        $estado = 1;
        $hora = date('h:i:s');
      
        $stmt = $this->db->prepare("SELECT f_insert_envio (:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p11,:p12,:p13,:p14,:p15,:p18,:p19); ");
        try 
        { 
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();                
                $stmt->bindParam(':p1',$idperiodo,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$idempleado,PDO::PARAM_INT);
                $stmt->bindParam(':p3',$_P['iddestino'],PDO::PARAM_INT);
                $stmt->bindParam(':p4',$_P['idchofer'],PDO::PARAM_STR);
                $stmt->bindParam(':p5',$_P['idvehiculo'],PDO::PARAM_STR);
                $stmt->bindParam(':p6',$_P['idremitente'],PDO::PARAM_STR);
                $stmt->bindParam(':p7',$_P['nrodocumentor'],PDO::PARAM_STR);
                $stmt->bindParam(':p8',$_P['remitente'],PDO::PARAM_STR);
                //$stmt->bindParam(':p9',$_P['idconsignado'],PDO::PARAM_STR);
                //$stmt->bindParam(':p10',$_P['nrodocumentoc'],PDO::PARAM_STR);
                $stmt->bindParam(':p11',$_P['consignado'],PDO::PARAM_STR);
                $stmt->bindParam(':p12',$_P['direccion'],PDO::PARAM_STR);
                $stmt->bindParam(':p13',$this->fdate($_P['fecha'],'EN'),PDO::PARAM_STR);
                $stmt->bindParam(':p14',$hora,PDO::PARAM_STR);      
                $stmt->bindParam(':p15',$this->tipo_documento,PDO::PARAM_INT);
                //$stmt->bindParam(':p16',$_P['serie'],PDO::PARAM_STR);
                //$stmt->bindParam(':p17',$_P['numero'],PDO::PARAM_STR);                
                $stmt->bindParam(':p18',$estado,PDO::PARAM_INT);
                $stmt->bindParam(':p19',$idoficina,PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetchAll();
                $idenvio = $row[0][0];                
                //$stmt2  = $this->db->prepare('INSERT INTO envio_detalle (idenvio,descripcion,precio,cantidad,estado) values(:p1,:p2,:p3,:p4,:p5)');
                $stmt2 = $this->db->prepare('CALL insert_envio_detalle(:p1,:p2,:p3,:p4,:p5)');
                $obj    = $_SESSION['envios'];
                $estado = 1;
                for($i=0;$i<$obj->item;$i++)
                { 
                    if($obj->estado[$i])
                    {
                        $stmt2->bindParam(':p1',$idenvio,PDO::PARAM_INT);
                        $stmt2->bindParam(':p2',$obj->descripcion[$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p3',$obj->precio[$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p4',$obj->cantidad[$i],PDO::PARAM_INT);
                        $stmt2->bindParam(':p5',$estado,PDO::PARAM_INT);
                        $stmt2->execute();
                    }
                }
                
            $this->db->commit();
            unset($_SESSION['envios']);
            return array('res'=>"1",'msg'=>'Bien!','idv'=>$idenvio);
        }
        catch(PDOException $e) 
        {
            $this->db->rollBack();
            return array('res'=>"2",'msg'=>'Error : '.$e->getMessage() . $str);
        }
        
    }    
    function anular($p) 
    {        
        $stmt = $this->db->prepare("CALL anular_envio(:p1)");
        $stmt->bindParam(':p1', $p, PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }    
    function getdata($idv)
    {
        $stmt = $this->db->prepare("select  e.serie,
                                            e.numero,
                                            concat(substring(e.fecha,9,2),'/',substring(e.fecha,6,2),'/',substring(e.fecha,1,4)) as fecha,
                                            e.hora,
                                            concat(em.nombre,' ',em.apellidos) as chofer,
                                            d.descripcion as destino,
                                            remitente.nombre as remitente,
                                            e.consignado,
                                            e.direccion,
                                            v.placa
                                    from envio as e inner join pasajero as remitente on remitente.idpasajero = e.idremitente
                                            inner join empleado as em on em.idempleado = e.idchofer and em.idtipo_empleado = 2
                                            inner join destino as d on d.iddestino = e.iddestino
                                            inner join vehiculo as v on v.idvehiculo = e.idvehiculo
                                        where e.idenvio = :id and e.estado = 1;");
        $stmt->bindParam(':id',$idv,PDO::PARAM_INT);
        $stmt->execute();
        $n = $stmt->rowCount();
        if($n>0)
        {
            $head = $stmt->fetchObject();       
            $stmt = $this->db->prepare("SELECT  descripcion,
                                            precio,
                                            cantidad
                                        FROM envio_detalle 
                                        WHERE idenvio = :id and estado = 1");
            $stmt->bindParam(':id',$idv,PDO::PARAM_INT);
            $stmt->execute();
            $detalle = $stmt->fetchAll();            
            return array(true,$head,$detalle);
        }
        else 
        {            
            return array(false,'','');
        }
    }
}
?>