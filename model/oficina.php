<?php
include_once("Main.php");
class oficina extends Main
{
   function index($query , $p, $c ) {
        $sql = "select o.idoficina,
                       o.descripcion,                       
                       s.descripcion
                       from oficina as o inner join sucursal as s on o.idsucursal = 
                       s.idsucursal 
                       where {$c} like :query
                       order by o.idoficina asc";
        $param = array(array('key'=>':query' , 'value'=>"%$query%" , 'type'=>'STR' ));
        $data['total'] = $this->getTotal( $sql, $param );
        $data['rows'] =  $this->getRow($sql, $param , $p );
        $data['rowspag'] =  $this->getRowPag($data['total'], $p );
        return $data;
    }
    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM oficina WHERE idoficina = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    function insert($_P ) {
        $stmt = $this->db->prepare("insert into oficina (descripcion, idsucursal, direccion, telefono) values(:p1,:p2,:p3,:p4)");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idsucursal'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['direccion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p4', $_P['telefono'] , PDO::PARAM_STR);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    function update($_P ) 
    {        
        $stmt = $this->db->prepare("update oficina set descripcion = :p1, idsucursal = :p2, direccion=:p3, telefono=:p4 where idoficina = :idoficina");
        $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p2', $_P['idsucursal'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['direccion'] , PDO::PARAM_STR);
        $stmt->bindParam(':p4', $_P['telefono'] , PDO::PARAM_STR);        
        $stmt->bindParam(':idoficina', $_P['idoficina'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM oficina WHERE idoficina = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>