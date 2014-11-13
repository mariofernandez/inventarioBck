<?php
	  require_once APPPATH.'libraries/phpexcel/PHPExcel.php';
      require_once APPPATH.'libraries/phpexcel/PHPExcel/IOFactory.php';
     //in controller add the following code to read spread sheet by active sheet
     //initialize php excel first  
     ob_end_clean();
     //define cachemethod
     $cacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
     $cacheSettings = array('memoryCacheSize' => '20MB');
     //set php excel settings
     PHPExcel_Settings::setCacheStorageMethod(
            $cacheMethod,$cacheSettings
          );

    $arrayLabel = array("A","B","C","D","E");
    //=== set object reader
    $objectReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objectReader->setReadDataOnly(true);

    $objPHPExcel = $objectReader->load("C:/carbook/avanzadas.xls");
    $objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');

    $starting = 1;
    $end      = 3;
    for($i = $starting;$i<=$end; $i++)
    {

       for($j=0;$j<count($arrayLabel);$j++)
       {
           //== display each cell value
           echo $objWorksheet->getCell($arrayLabel[$j].$i)->getValue();
       }
    }
     //or dump data
     $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
     var_dump($sheetData);

     //see also the following link
    // http://blog.mayflower.de/561-Import-and-export-data-using-PHPExcel.html
     //----------- import in another style around 5000 records ------
     $this->benchmark->mark('code_start');
    //=== change php ini limits. =====
    $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    $cacheSettings = array( ' memoryCacheSize ' => '50MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    //==== create excel object of reader
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    //$objReader->setReadDataOnly(true);
    //==== load forms tashkil where the file exists
    $objPHPExcel = $objReader->load("./forms/5000records.xlsx");
    //==== set active sheet to read data
    $worksheet  = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');


    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $nrColumns          = ord($highestColumn) - 64;
    $worksheetTitle     = $worksheet->getTitle();

    echo "<br>The worksheet ".$worksheetTitle." has ";
    echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
    echo ' and ' . $highestRow . ' row.';
    echo '<br>Data: <table border="1"><tr>';
    //----- loop from all rows -----
    for ($row = 1; $row <= $highestRow; ++ $row) 
    {
        echo '<tr>';
        echo "<td>".$row."</td>";
        //--- read each excel column for each row ----
        for ($col = 0; $col < $highestColumnIndex; ++ $col) 
        {
            if($row == 1)
            {
                // show column name with the title
                 //----- get value ----
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                //$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                echo '<td>' . $val ."(".$row." X ".$col.")".'</td>';
            }
            else
            {
                if($col == 9)
                {
                    //----- get value ----
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    //$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                    echo '<td>zone ' . $val .'</td>';
                }
                else if($col == 13)
                {
                    $date = PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow($col, $row)->getValue())->format('Y-m-d');
                    echo '<td>' .dateprovider($date,'dr') .'</td>';
                }
                else
                {
                     //----- get value ----
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    //$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                    echo '<td>' . $val .'</td>';
                }
            }
        }
        echo '</tr>';
    }
    echo '</table>';

?>