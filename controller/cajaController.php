<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/caja.php';
class cajaController extends Controller
{
    public function msg()
    {
        $data = array();
        $view = new View();                        
        $view->setTemplate( '../view/caja/_msg.php' );        
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
    public function msgc()
    {
        $data = array();
        $view = new View();                        
        $view->setTemplate( '../view/caja/_msgc.php' );        
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
    public function aperturar()
    {
        $obj = new caja();
        $data = array();
        $data = $obj->aperturar($_POST['fecha']);
        if($data[0])
        {

            $_SESSION['fecha_caja'] = $_POST['fecha'];            
            $_SESSION['id_caja'] = $obj->getCaja($_SESSION['turno'],$_POST['fecha']);
            $_SESSION['caja'] = 1; // Caja aperturada y correcta
            print_r(json_encode(array(1,'OK')));
        }
        else 
        {
            print_r(json_encode(array(0,$data[1])));            
        }
    }
    public function vcierre()
    {
        $data = array();
        $view = new View();                        
        $view->setTemplate( '../view/caja/_cierre.php' );        
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
    public function cerrar()
    {
        $obj = new caja();
        $data = array();
        $saldo = $_POST['s'];        
        $data = $obj->cerrar($saldo);
        if($data['res']=="1")
        {
            $html = $this->resumenCaja($data['rows']);
            print_r(json_encode(array(1,$html)));
        }
        else {
            print_r(json_encode(array(0,$data['msg'])));
        }
    }

    public function resumenCaja($r)
    {
        $data = array();
        $view = new View();                       
        $data['saldo_inicial'] = $r[0];
        $data['saldo_declarado'] = $r[1];
        $data['saldo_real'] = $r[2];
        $data['fecha'] = $r[3];
        $data['turno'] = $r[4];
        $data['idusuario'] = $r[5];
        $view->setData($data);
        $view->setTemplate( '../view/caja/_resumen.php' );        
        $view->setlayout( '../template/layout.php' );
        return $view->renderPartial();
    }
}
?>