        <?php
        require('../funciones/fpdf/fpdf.php');
         
        $var = 3;
         
        $pdf=new FPDF();
         
        for($i=0; $i<$var; $i++){
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'¡Hola, Mundo!' . $i);
        }
        $pdf->Output();
        ?>