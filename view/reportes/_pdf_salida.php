<?php
session_start();
require("../lib/fpdf/fpdf.php");
class PDF extends FPDF
{
        var $article;
        var $unidad;
        var $maxw;
        var $widths;
        
        public function setValues($article,$unidad,$maxw,$widths)
        {
            $this->article = $article;
            $this->unidad = $unidad;
            $this->maxw = $maxw;
            foreach($widths as $w)
            {
                $this->widths[] = $w;
            }
        }
        
	function Header()
	{                
                $maxw = $this->maxw;
                $this->SetLineWidth(0.1);                
                //$this->Image('../../../images/logo.jpg',10,5,28);
                $this->SetFont('Arial','B',12);
                $this->Ln();
                $this->SetXY(9,8);
                $this->Write(0,'REPORTE DE SALIDAS');		
                $this->Line(9,10,$maxw,10);
                $this->SetFont('Times','',9);
                $this->SetXY(9,12);
                //$this->Write(0, 'ARTICULO: '.$this->article.'     UNIDAD: '.$this->unidad);
                $this->SetFont('Times','',7);
                $this->SetXY($maxw-17,12);
                $fecha = date('d-M-Y');
                $this->Write(0,$fecha);
                $this->Ln(8);		
                
		$this->SetFont('Times','B',7);		
		$this->SetFillColor(245, 245, 245);
                $this->SetTextColor(0,0,0); 
                $this->SetDrawColor(0,0,0);
                $fill = true;
                $h = 4;
                $border = "TBLR";
                
		        $this->Cell($this->widths[0], $h, 'ITEM', $border, 0, 'C',$fill);                
                $this->Cell($this->widths[1], $h, 'FECHA PAGO', $border, 0, 'C',$fill);
                $this->Cell($this->widths[2], $h, 'HORA PAGO', $border, 0, 'C',$fill);
                $this->Cell($this->widths[3], $h, 'CHOFER', $border, 0, 'C',$fill);
                $this->Cell($this->widths[4], $h, 'VEHICULO', $border, 0, 'C',$fill);
				$this->Cell($this->widths[5], $h, 'NUMERO', $border, 0, 'C',$fill);
                $this->Ln();
                
	}
	function Footer()
	{
            $this->SetY(-13);
            $this->SetFont('Arial','I',7);
            $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
	}
	function ffecha($fecha)
        {
            $n = explode("-",$fecha);
            return $n[2]."/".$n[1]."/".$n[0];
        }	       
        function background($i)
        {
            if($i%2==0)
            {
                $this->SetFillColor(245, 245, 245);
                $this->SetTextColor(0,0,0);
            }
            else 
            {
                $this->SetFillColor(255, 255, 255);
                $this->SetTextColor(0,0,0);
            }
        }
	function FancyTable($rowsi,$rows)
	{
            $tingreso = 0;
            $tegresos = 0;
            $saldo = 0;
            $i = 0;
            $h = 4;
            $w = $this->widths;
            $border = 'BLRT';
            $this->SetFont('Times','',5);	
            $this->SetLineWidth(0.1);   
//            foreach ($rowsi as $r) 
//            {            
//                $i += 1;
//                $this->Cell($w[0], $h,str_pad($i, 3, '0', 0), $border, 0, 'C', false);
//                $this->Cell($w[1], $h,$r['f'], $border, 0, 'C', false);                
//                $this->Cell($w[2]+$w[3]+$w[4], $h,"*** SALDO INICIAL ***", $border, 0, 'C', false);                
//                $this->Cell($w[5], $h,$r['cantidad'], $border, 0, 'R', false);                
//                $this->Cell($w[6], $h,"", $border, 1, 'L', false);                                
//            }

            foreach($rowsi as $r)
            {
                $i += 1;
                $this->background(1);
                /*if($r['tipo']==1)
                {
                    $ingreso = $r['cant'];
                    $egreso = '';
                    $tingreso += $ingreso;
                }
                else
                {
                    $ingreso = '';
                    $egreso = $r['cant'];
                    $tegreso += $egreso;
                }*/
                $fill = true;
                $this->Cell($w[0], $h,str_pad($i, 3, '0', 0), $border, 0, 'C', $fill);
                $this->Cell($w[1], $h,$this->ffecha($r[0]), $border, 0, 'C', $fill);  
                //$nh = $this->nLineaBreak($w[2], $h, utf8_decode($r['doc']), 'L');
                $this->MultiCell($w[2], $h, $r[1], $border, 'C', $fill);                
                $this->Cell($w[3], $h,$r[2], $border, 0, 'C', $fill);
                $this->Cell($w[4], $h,$r[3], $border, 0, 'C', $fill);
                $this->Cell($w[5], $h,$r[4], $border, 0, 'C', $fill);
				
                //$nh = $this->nLineaBreak($w[6], $h, utf8_decode($r['obs']), 'L');
                //$this->MultiCell($w[6], $h/$nh, utf8_decode($r['obs']), $border, 'L', $fill);
                $y = $this->GetY();
                $this->SetXY(10,$y+$h);
                
            }
			
	}
}

$pdf=new PDF();

$maxw=200;
$w = array(15,30,30,40,40,30);
$pdf->setValues($rowsi[0]['fecha'], $rowsi[0]['hora'], $maxw,$w);
$orientacion = 'P';
$pdf->AddPage($orientacion);
$pdf->AliasNbPages();
$pdf->FancyTable($rowsi,$rows);
$pdf->Output();
?>