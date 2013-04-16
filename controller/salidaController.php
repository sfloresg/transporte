<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/salida.php';
require_once '../model/tipo_pasajero.php';
class salidaController extends Controller
{
   public function index() {
        if (!isset($_GET['p'])){$_GET['p']=1;}
        if(!isset($_GET['q'])){$_GET['q']="";} 
        if(!isset($_GET['criterio'])){$_GET['criterio']="chofer.nombre";} 
        $obj = new salida();
        $data = array();
        $data['data'] = $obj->index($_GET['q'],$_GET['p'],$_GET['criterio']);
        $data['query'] = $_GET['q'];
        $data['pag'] = $this->Pagination(array('rows'=>$data['data']['rowspag'],'url'=>'index.php?controller=salida&action=index','query'=>$_GET['q'],'trows'=>$data['data']['total']));
        $this->registros = $data['data']['rows'];
        $this->columnas = array("ID"=>array('ancho'=>'5','align'=>'center','title'=>'Codigo'),
                                "FECHA"=>array('ancho'=>'10','align'=>'center'),
                                "HORA"=>array('ancho'=>'8','align'=>'center'),
                                "CHOFER"=>array(),
                                "VEHICULO"=>array('ancho'=>9,'align'=>'center'),
                                "TICKET"=>array('ancho'=>9,'align'=>'center','colspan'=>2),
                                "ESTADO"=>array('ancho'=>'10','align'=>'center'),
                                "&nbsp;"=>array('ancho'=>9,'align'=>'center')
                                );         
        $this->busqueda = array("chofer.nombre"=>"Chofer",
                                "vehiculo.placa"=>"Placa de Vehiculo");
        $this->asignarAccion('eliminar',false);
        $this->asignarAccion('editar',false);
        $this->asignarAccion('ver',true);
        $data['grilla'] = $this->grilla("salida",$data['pag']);
        $view = new View();
        $view->setData($data);
        $view->setTemplate( '../view/salida/_index.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
   public function create() 
   {
        $data = array();
        $view = new View();          
        $data['itinerario'] = $this->Select(
                                            array(
                                                    'id'=>'iditinerario',
                                                    'name'=>'iditinerario',
                                                    'table'=>'vitinerario2'
                                                    )
                                                 );
        $data['chofer'] = $this->Select(
                                            array(
                                                    'id'=>'idchofer',
                                                    'name'=>'idchofer',
                                                    'table'=>'vchofer'
                                                    )
                                                 );
        $data['vehiculo'] = $this->Select(
                                            array(
                                                    'id'=>'idvehiculo',
                                                    'name'=>'idvehiculo',
                                                    'table'=>'vvehiculo'
                                                    )
                                                 );
        
        $data['more_options'] = $this->more_options('salida');        
        $view->setData($data);
        $view->setTemplate( '../view/salida/_form.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }   
    public function payticket() 
    {
        $obj = new salida();
        $id = (int)$_POST['i'];
        $r = $obj->payticket($id);
        if($r['res']=="1")
        {
            //$html = $this->ticket($id,$_POST['n']);
            print_r(json_encode(array('1',$r['msg'])));
        }
        else 
        {
            print_r(json_encode(array('0',$r['msg'])));
        }
        
    }
    public function ticket($id,$n)
    {
        //Funcion para obtener
        $view = new View();
        $data = array();
        $data['id']=$id;
        $data['n']=$n;
        $view->setData($data);
        $view->setTemplate( '../view/salida/_ticket.php');
        return $view->renderPartial();
    }
    public function despachar()
    {
        $obj = new salida();
        $id = (int)$_POST['i'];
        $r = $obj->despachar($id);
        if($r['res']=="1")
        {
            print_r(json_encode(array('1',$r['msg'])));
        }
        else 
        {
            print_r(json_encode(array('0',$r['msg'])));
        }
    }
    public function vtipo_pasajero()
    {
        $data = array();
        $view = new View(); 
        $otp = new tipo_pasajero();
        $data['rows'] = $otp->gettipos();
        $view->setData($data);
        $view->setTemplate( '../view/tipo_pasajero/_tipos.php' );
        return $view->renderPartial();        
    }
    public function show() 
    {        
        $obj = new salida();
        $obj = $obj->edit($_GET['id']);
        $data = array();
        $data['more_options'] = $this->more_options('salida');
        $data['itinerario'] = $this->Select(
                                            array(
                                                    'id'=>'iditinerario',
                                                    'name'=>'iditinerario',
                                                    'table'=>'vitinerario2',
                                                    'disabled'=>'disabled',
                                                    'code'=>$obj->iditinerario
                                                    )
                                                 );
        $data['chofer'] = $this->Select(
                                            array(
                                                    'id'=>'idchofer',
                                                    'name'=>'idchofer',
                                                    'table'=>'vchofer',
                                                    'disabled'=>'disabled',
                                                    'code'=>$obj->idchofer
                                                    )
                                                 );
        $data['vehiculo'] = $this->Select(
                                            array(
                                                    'id'=>'idvehiculo',
                                                    'name'=>'idvehiculo',
                                                    'table'=>'vvehiculo',
                                                    'disabled'=>'disabled',
                                                    'code' => $obj->idvehiculo
                                                    )
                                                 );
        
        $view = new View();        
        $data['obj'] = $obj;
        $view->setData($data);
        $view->setTemplate( '../view/salida/_form.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
    public function result()
    {
        $data = array();
        $view = new View();        
        $data['more_options'] = $this->more_options('salida');        
        $data['idsalida'] = (int)$_GET['is'];
        $view->setData($data);
        $view->setTemplate( '../view/salida/_result.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }    
    public function save()
    {      
        $obj = new salida();
        if ($_POST['idsalida']=='') 
        {
            $p = $obj->insert($_POST);
            if ($p['res']=='1')
            {
                header('Location: index.php?controller=salida&action=result&is='.$p['ids']);                
            }
            else 
            {
                $data = array();
                $view = new View();
                $data['msg'] = $p['msg'];
                $data['url'] =  'index.php?controller=salida';
                $view->setData($data);
                $view->setTemplate( '../view/_error_app.php' );
                $view->setlayout( '../template/layout.php' );
                $view->render();
            }
        }
    }
    public function anular()
      {
        $obj = new salida();
        $p = $obj->anular($_GET['id']);
        if ($p[0]){
            header('Location: index.php?controller=salida');
        } 
        else {
            $data = array();
            $view = new View();
            $data['msg'] = $p[1];
            $data['url'] =  'index.php?controller=salida';
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            $view->setlayout( '../template/layout.php' );
            $view->render();
        }
      }
   
    public function printer()
    {
        $data = array();
        $view = new View();                
        $obj = new salida();
        $result = $obj->getdata((int)$_GET['iv']);
        if($result[0])
        {
            $data['head'] = $result[1]; 
            $data['detalle'] = $result[2];
            $view->setData($data);
            $view->setTemplate( '../view/salida/_pdf_'.$result[3].'.php');
            $view->setlayout( '../template/empty.php' );
            $view->render();
        }
        else 
        {
            $data = array();
            $view = new View();
            $data['msg'] = 'No se ha podido encontrar la salida solicitada.';
            $data['url'] =  'index.php?controller=salida';
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            $view->setlayout( '../template/layout.php' );
            $view->render();
        }
        
    }
}
?>