<?php
require_once '../lib/controller.php';
require_once '../lib/view.php';
require_once '../model/envio.php';
require_once '../model/tipo_pasajero.php';

class envioController extends Controller
{
   public function index() 
   {
        if (!isset($_GET['p'])){$_GET['p']=1;}
        if(!isset($_GET['q'])){$_GET['q']="";} 
        if(!isset($_GET['criterio'])){$_GET['criterio']="remitente.nombre";} 
        $obj = new envio();
        $data = array();
        $data['data'] = $obj->index($_GET['q'],$_GET['p'],$_GET['criterio']);
        $data['query'] = $_GET['q'];
        $data['pag'] = $this->Pagination(array('rows'=>$data['data']['rowspag'],'url'=>'index.php?controller=envio&action=index','query'=>$_GET['q'],'trows'=>$data['data']['total']));
        $this->registros = $data['data']['rows'];
        $this->columnas = array("ID"=>array('ancho'=>'5','align'=>'center','title'=>'Codigo'),
                                "FECHA"=>array('ancho'=>'10','align'=>'center'),
                                "REMITENTE"=>array(),
                                "CONSIGNADO"=>array(),
                                "NRO"=>array('ancho'=>12,'align'=>'center'),
                                "ESTADO"=>array('ancho'=>'10','align'=>'center'),
                                "&nbsp;"=>array('ancho'=>3,'align'=>'center')
                                );         
        $this->busqueda = array("remitente.nombre"=>"Remitente",
                                "consignado.nombre"=>"Consignado"
                                );
        $this->asignarAccion('eliminar',false);
        $this->asignarAccion('editar',false);
        $this->asignarAccion('ver',true);
        $data['grilla'] = $this->grilla("envio",$data['pag']);
        $view = new View();
        $view->setData($data);
        $view->setTemplate( '../view/envio/_index.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
   public function create() 
   {
        $data = array();
        $view = new View();          
        $data['destino'] = $this->Select(
                                            array(
                                                    'id'=>'iddestino',
                                                    'name'=>'iddestino',
                                                    'table'=>'vdestino'
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
        $data['tipos'] = $this->vtipo_pasajero();
        $data['more_options'] = $this->more_options('envio');
        $data['detalle'] = $this->viewDetalle();
        $view->setData($data);
        $view->setTemplate( '../view/envio/_form.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
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
        
        $obj = new envio();
        $obj = $obj->edit($_GET['id']);
        $data = array();
        $data['more_options'] = $this->more_options('envio');
        $data['destino'] = $this->Select(
                                            array(
                                                    'id'=>'iddestino',
                                                    'name'=>'iddestino',
                                                    'table'=>'destino',
                                                    'code'=>$obj->iddestino,
                                                    'disabled'=>'disabled'
                                                    )
                                                 );
        $data['chofer'] = $this->Select(
                                            array(
                                                    'id'=>'idchofer',
                                                    'name'=>'idchofer',
                                                    'table'=>'vchofer',
                                                    'code'=>$obj->idchofer,
                                                    'disabled'=>'disabled'
                                                    )
                                                 );
        $data['vehiculo'] = $this->Select(
                                            array(
                                                    'id'=>'idvehiculo',
                                                    'name'=>'idvehiculo',
                                                    'table'=>'vvehiculo',
                                                     'code'=>$obj->idvehiculo,
                                                     'disabled'=>'disabled'
                                                    )
                                                 );
        $data['detalle'] = $this->viewDetalle('readonly');
        $view = new View();        
        $data['obj'] = $obj;
        $view->setData($data);
        $view->setTemplate( '../view/envio/_form.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }
    public function result()
    {
        $data = array();
        $view = new View();        
        $data['more_options'] = $this->more_options('envio');        
        $data['idenvio'] = (int)$_GET['iv'];
        $view->setData($data);
        $view->setTemplate( '../view/envio/_result.php' );
        $view->setlayout( '../template/layout.php' );
        $view->render();
    }    
    public function save()
    {      
        $obj = new envio();
        $result = array();
        if ($_POST['idenvio']=='') 
        {
            $p = $obj->insert($_POST);
            
            if ($p['res']=='1')
            {
                $result = array(1,'SE HA REGISTRADO CORRECTAMENTE EL ENVIO',$p['idv']);
            } 
            else 
            {
                $result = array(2,'HA OCURRIDO UN ERROR, FAVOR DE ACTUALIZAR LA PAGINA (F5) Y VOLVER A INTENTARLO','');
            }
            print_r(json_encode($result));
        }
    }
    public function anular()
      {
        $obj = new envio();
        $p = $obj->anular($_GET['id']);
        if ($p[0])
        {
            header('Location: index.php?controller=envio');
        } 
        else 
        {
            $data = array();
            $view = new View();
            $data['msg'] = $p[1];
            $data['url'] =  'index.php?controller=envio';
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            $view->setlayout( '../template/layout.php' );
            $view->render();
        }
      }
    public function add()
    {
        $r = $_SESSION['envios']->add($_POST['descripcion'],$_POST['precio'],$_POST['cantidad']);
        if($r){print_r(json_encode(array("resp"=>"1","data"=>$this->viewDetalle())));}
            else{print_r(json_encode(array("resp"=>"0","data"=>"ESTA DESCRIPCION YA FUE AGREGADO AL DETALLE")));}
    }    
    public function quit()
    {
        $_SESSION['envios']->quit($_POST['i']);
        echo $this->viewDetalle();
    }    
    public function getDetalle()
    {
        echo $this->viewDetalle();
    }
    public function viewDetalle($type = null)
    {
        $view = new View();
        $data = array();        
        $view->setData($data);
        if($type!="readonly")
        {
            $view->setTemplate('../view/envio/_detalle.php');
        }
        else 
        {
            $view->setTemplate('../view/envio/_detalle_readonly.php');
        }
        
        $view->setlayout( '../template/layout.php' );
        return $view->renderPartial();        
    }
    public function printer()
    {
        $data = array();
        $view = new View();                
        $obj = new envio();
        $result = $obj->getdata((int)$_GET['iv']);
        if($result[0])
        {
            $data['head'] = $result[1]; 
            $data['detalle'] = $result[2];
            $view->setData($data);
            $view->setTemplate( '../view/envio/_print_envio.php');
            $view->setlayout( '../template/empty.php' );
            $view->render();
        }
        else 
        {
            $data = array();
            $view = new View();
            $data['msg'] = 'No se ha podido encontrar el envio solicitado a imprimir.';
            $data['url'] =  'index.php?controller=envio';
            $view->setData($data);
            $view->setTemplate( '../view/_error_app.php' );
            $view->setlayout( '../template/layout.php' );
            $view->render();
        }
        
    }
}
?>