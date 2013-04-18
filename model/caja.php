<?php
include_once("Main.php");
class caja extends Main
{
   
    function verifApertura($turno,$fecha)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM caja WHERE estado = 1 and turno = {$turno} and fecha = '{$fecha}' and idusuario = :idu ");        
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);       
        $stmt->execute();
        $r = $stmt->fetchAll();
        $n = $r[0][0];
        if($n==0)
        {
            return false;
        }
        else 
        {
            return true;
        }
    }
    function verifAperturaAll($turno)
    {
        $stmt = $this->db->prepare("SELECT fecha,idcaja FROM caja WHERE estado = 1 and turno = {$turno} and idusuario = :idu");    
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);
        $stmt->execute();
        $r = $stmt->fetchObject();
        $n = $stmt->rowCount();
        if($n>0)
        {
            return array(true,$r->fecha,$r->idcaja);
        }
        else {
            return array(false,'','');
        }
    }
    function getCaja($turno,$fecha)
    {
        $stmt = $this->db->prepare("SELECT idcaja FROM caja WHERE estado = 1 and turno = {$turno} and fecha = '{$this->fdate($fecha,'EN')}' and idusuario = :idu ");
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    function aperturar($fecha)
    {
        //Validar que la fecha y el turno no esten aperturados o ya esten cerrados
        //Verificamos si la caja en la fecha, turno y usuario ya estan aperturadas.
        $fecha = $this->fdate($fecha,'EN');
        $stmt = $this->db->prepare("SELECT count(*) FROM caja WHERE estado = 1 and turno = :turno and fecha = :fecha and idusuario = :idu ");        
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);
        $stmt->bindParam(':turno',$_SESSION['turno'],PDO::PARAM_INT);
        $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
        $stmt->execute();
        $r = $stmt->fetchAll();
        $n = $r[0][0];
        if($n==0)
        {
            //Verificamos si la caja en la fecha, turno y usuario ya fueron cerradas
            $stmt = $this->db->prepare("SELECT count(*) FROM caja WHERE estado = 2 and turno = :turno and fecha = :fecha and idusuario = :idu ");        
            $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);
            $stmt->bindParam(':turno',$_SESSION['turno'],PDO::PARAM_INT);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            $stmt->execute();
            $r = $stmt->fetchAll();
            $n = $r[0][0];
            if($n==0)
            {
                //Obtenemos el saldo anterior para definir el saldo inicial
                $si = $this->getSaldoInicial($_SESSION['turno']);
                //aperturar caja
                $stmt = $this->db->prepare("INSERT into caja(idusuario,fecha,turno,estado,saldo_inicial) values(:p0,:p1,:p2,1,:si)");
                $stmt->bindParam(':p0',$_SESSION['idempleado'],PDO::PARAM_STR);
                $stmt->bindParam(':p1',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':p2',$_SESSION['turno'],PDO::PARAM_INT);
                $stmt->bindParam(':si',$si,PDO::PARAM_INT);
                $p1 = $stmt->execute();
                $p2 = $stmt->errorInfo();
                return array($p1 , $p2[2]);
            }
            else 
            {
                return array(false,"YA SE A CERRADO LA CAJA EN ESTA FECHA, TURNO Y USUARIO");
            }
            
        }        
        else {
            return array(false,"YA SE A APERTURADO UNA CAJA EN ESTA FECHA, TURNO Y USUARIO");
        }
    }
    
    function cerrar($saldo)
    {
        //Obtenemos el saldo del sistema
        $ssistema = $this->getSaldoSistema();
        //--
        $sdeclarado = (float)$saldo;        
        $turno = $_SESSION['turno'];
        $fecha = $this->fdate($_SESSION['fecha_caja'],'EN');        
        $stmt = $this->db->prepare("UPDATE caja set saldo_declarado = :p1, saldo_real = :p2, estado = 2 WHERE fecha = :f and turno = :t and idusuario = :idu");
        try 
        { 
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();                
                $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);
                $stmt->bindParam(':p1',$sdeclarado,PDO::PARAM_INT);
                $stmt->bindParam(':p2',$ssistema,PDO::PARAM_INT);
                $stmt->bindParam(':f',$fecha,PDO::PARAM_STR);
                $stmt->bindParam(':t',$turno,PDO::PARAM_INT);
                $stmt->execute();
             $this->db->commit();
             //Obtenemos los datos para el reporte cierre de caja
             $rows = $this->getDataCaja($fecha,$turno);
             $_SESSION = array();
             session_destroy();
             return array('res'=>"1",'rows'=>$rows);
        }
        catch(PDOException $e)
        {
            $this->db->rollBack();
            return array('res'=>"2",'msg'=>'Error : '.$e->getMessage());
        }        
    }
    function getSaldoInicial($turno)
    {
        $stmt = $this->db->prepare("SELECT coalesce(saldo_inicial+saldo_real,0) 
                                    from caja 
                                    where estado=2 and turno = :t  
                                          and idusuario = :idu
                                    order by idcaja 
                                    desc limit 1");
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);
        $stmt->bindParam(':t',$turno,PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetchAll();
        $si = $r[0][0];
        return $si;
    }
    function getSaldoSistema()
    {
        $turno = $_SESSION['turno'];
        $fecha = $_SESSION['fecha_caja'];
        $stmt = $this->db->prepare("SELECT SUM(md.monto*md.cantidad) as saldo
                                    FROM movimiento as m inner join movimiento_detalle as md on 
                                        md.idmovimiento = m.idmovimiento inner join empleado as e on
                                        e.idempleado = m.idempleado
                                    WHERE m.estado = 1 and m.fecha = '{$fecha}' and e.turno = {$turno}
                                          and m.idempleado = :idu ");
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);                        
        $stmt->execute();
        $r = $stmt->fetchObject();
        $saldo = (double)$r->saldo;
        return $saldo;
    }
    function getDataCaja($fecha,$turno)
    {
        $stmt = $this->db->prepare("SELECT saldo_inicial,saldo_declarado,saldo_real,fecha,turno,idusuario
                                    from caja where fecha = '{$fecha}' and turno = {$turno} and idusuario = :idu ");
        $stmt->bindParam(':idu',$_SESSION['idempleado'],PDO::PARAM_STR);        
        $stmt->execute();
        $r = $stmt->fetchObject();
        return array($r->saldo_inicial,$r->saldo_declarado,$r->saldo_real,$r->fecha,$r->turno,$r->idusuario);
    }        
}
?>