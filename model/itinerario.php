<?php
include_once("Main.php");
class itinerario extends Main
{
   function index($query , $p, $c ) {
        $sql = "select i.iditinerario,
                       o.descripcion as origen,
                       d.descripcion as destino,
                       i.precio
                       from itinerario as i inner join destino as d on d.iddestino = i.destino
                            inner join destino as o on o.iddestino = i.origen
                       where {$c} like :query
                       order by iditinerario asc";
        $param = array(array('key'=>':query' , 'value'=>"%$query%" , 'type'=>'STR' ));
        $data['total'] = $this->getTotal( $sql, $param );
        $data['rows'] =  $this->getRow($sql, $param , $p );
        $data['rowspag'] =  $this->getRowPag($data['total'], $p );
        return $data;
    }
    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM itinerario WHERE iditinerario = :id");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    function insert($_P ) {
        $stmt = $this->db->prepare("insert into itinerario (origen,destino, precio) values(:p1,:p2,:p3)");
        $stmt->bindParam(':p1', $_P['idorigen'] , PDO::PARAM_INT);
        $stmt->bindParam(':p2', $_P['iddestino'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['precio'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    function update($_P ) {
        $stmt = $this->db->prepare("update itinerario set origen = :p1, destino = :p2, precio = :p3 where iditinerario = :iditinerario");
        $stmt->bindParam(':p1', $_P['idorigen'] , PDO::PARAM_INT);
        $stmt->bindParam(':p2', $_P['iddestino'] , PDO::PARAM_INT);
        $stmt->bindParam(':p3', $_P['precio'] , PDO::PARAM_INT);
        $stmt->bindParam(':iditinerario', $_P['iditinerario'] , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
    function delete($_P ) {
        $stmt = $this->db->prepare("DELETE FROM itinerario WHERE iditinerario = :p1");
        $stmt->bindParam(':p1', $_P , PDO::PARAM_INT);
        $p1 = $stmt->execute();
        $p2 = $stmt->errorInfo();
        return array($p1 , $p2[2]);
    }
}
?>