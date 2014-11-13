<?php

  /*********************************************
  Sólo necesita alUnidadesVinHdn para funcionar
  *********************************************/

  $savePath = "./../";

	require_once("../funciones/fpdf/fpdf.php");
	require_once("../funciones/barcode.php");
  require_once("../funciones/generales.php");
  require_once("../funciones/construct.php");

  $success = true;

  $vinArr = explode('|', substr($_REQUEST['alUnidadesVinHdn'], 0, -1));
  if (in_array('', $vinArr)) {
    $success = false;
  }

  $pdf = new FPDF('P', 'mm', array(100, 150));

  if ($success) {
    for ($nInt=0; $nInt < sizeof($vinArr); $nInt++) { 
      $sqlGetDataStr = "SELECT h.vin, h.distribuidor, lp.fila, lp.lugar, h.claveMovimiento, dc.tipoDistribuidor, ".
                       "dc.observaciones AS puerto, dc.descripcionCentro , p.pais, h.localizacionUnidad AS patio, h.fechaEvento, pl.plaza ".
                       "FROM caDistribuidoresCentrosTbl dc, caDireccionesTbl di, caColoniasTbl co, ".
                       "caMunicipiosTbl m, caEstadosTbl e, caPaisesTbl p, caPlazasTbl pl, alHistoricoUnidadesTbl h ".
                       "LEFT JOIN allocalizacionpatiostbl lp ON lp.vin = h.vin ".
                       "WHERE dc.distribuidorCentro = h.distribuidor ".
                       "AND pl.idPlaza = dc.idPlaza ".
                       "AND di.direccion = dc.direccionEntrega ".
                       "AND co.idColonia = di.idColonia ".
                       "AND m.idMunicipio = co.idMunicipio ".
                       "AND e.idEstado = m.idEstado ".
                       "AND p.idPais = e.idPais ".
                       "AND h.vin = '".$vinArr[$nInt]."' ".
                       "AND fechaEvento = (SELECT MAX(h1.fechaEvento) ".
                       "FROM alHistoricoUnidadesTbl h1 ". 
                       "WHERE h1.vin = h.vin AND (h1.claveMovimiento = 'EP' OR h1.claveMovimiento = 'PR')) ";

      $rs = fn_ejecuta_query($sqlGetDataStr);
      $data = $rs['root'][0];
      if (sizeof($data) > 0) {
        $pdf = generarPdf($data, $pdf, $nInt); 
      }
    }

    $pdf->Output('Etiquetas.pdf', I);
  } else {
    echo "ERROR";
  }

  function generarPdf($data, $pdf, $n){
    //Solo para pruebas, por defecto 0
    $border = 0;
    if($data['tipoDistribuidor'] == 'DX')
    {
      $paisPlaza = $data['pais'];
      $puertoNombre = substr($data['puerto'], 0, 9);
    } else {
      $paisPlaza = $data['plaza'];
      $puertoNombre = substr($data['descripcionCentro'],0, 9);
    }

    if(sizeof($data) > 0){
      $pdf->AddPage();
      $pdf->AddFont('skyline', '', 'skyline-reg.php');
      $pdf->SetMargins(0.2, 0.2, 0.2,0.2);
      if ($n > 0) {
        $pdf->SetY(10);
        $pdf->SetX(10);
      }

      //Código Distribuidor y País
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(40,10,$data['distribuidor'], $border,0, 'L');  
      $pdf->SetY(25);
      $pdf->SetLeftMargin(5);
      $pdf->SetFont('skyline','',70);
      $pdf->Cell(40,20,$paisPlaza, $border,0, 'C');
      
      //Patio, Fila, Cajon
      $pdf->SetY(10);
      $pdf->SetLeftMargin(55);
      $pdf->SetFont('Arial','B',17);
      $pdf->Cell(40, 10, 'Patio: '.$data['patio'],$border,1,'L');
      $pdf->Cell(40, 10, 'Fila: '.$data['fila'],$border,1,'L');
      $pdf->Cell(40, 10, 'Cajon: '.$data['lugar'],$border,1,'L');

      //Puerto
      $pdf->setY(55);
      $pdf->SetX(0);
      $pdf->SetLeftMargin(0.2);
      $pdf->SetFont('skyline','',125);
      $pdf->Cell(0,30,$puertoNombre, $border,1, 'C');

      //Fecha y Hora
      if (!isset($data['fechaEvento'])) {
        $data['fechaEvento'] = date("Y-m-d H:i:s");
      }
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(0,10,$data['fechaEvento'], $border,1, 'C'); 

      //Avanzada
      $pdf->SetFont('skyline','',66);
      $pdf->Cell(0,25,substr($data['vin'], -8), $border,1, 'C');    
      
      //****************
      //CODIGO DE BARRAS
      //****************

      $fontSize = 4; // GD1 in px ; GD2 in point
      $marge = 10; // between barcode and hri in pixel
      $x = 400;  // barcode center x
      $y = 50;  // barcode center y
      $height = 100;  // barcode height
      $width = 3;  // barcode width
      $angle = 0; // rotation in degrees 
      $code = $data['vin']; // vin code for barcode
      $type = 'code39'; // barcode type

      //Se crea la imagen para usar para el codigo de barras
      $im = imagecreatetruecolor(800, 120);
      $black = imagecolorallocate($im, 0x00, 0x00, 0x00);
      $white = imagecolorallocate($im,0xff,0xff,0xff);
      imagefilledrectangle($im, 0, 0, 800, 120, $white);
      imagestring($im, 5, 335, 100, $data['vin'], imagecolorallocate($im, 0, 0, 0));

      //Se genera el Codigo de barras
      $data = Barcode::gd($im, $black, $x, $y, $angle, $type,   array('code'=>$code), $width, $height);
      Header('Content-type: image/gif');
      imagegif($im, 'barcodeTemp.gif');

      //Código de Barras y vin
      $pdf->Image('barcodeTemp.gif',-1.,120, -200);

      // I para mostrar, F para salvar
      /*$saveFile = $savePath.$code.'.pdf';
      $pdf->Output($code.'.pdf', 'D');
      //$pdf->Output($saveFile, 'F');*/
      
      if (file_exists('barcodeTemp.gif')) {
          unlink('barcodeTemp.gif');
      }

      imagedestroy($im);

      return $pdf;

    } else {
      echo json_encode(array('success'=>false, 'errorMessage'=>$_SESSION['error_sql']." <br> ".$sqlGetDataStr));
    }
  }
?>