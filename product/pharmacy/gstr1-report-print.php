<?php include('include/usertypecheck.php');?>
<?php

    $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : ''; 
    $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
    
    
    $data = getGSTR1Report($from, $to, 0);
    
	require_once 'Classes/PHPExcel.php';
	require_once 'Classes/PHPExcel/IOFactory.php';

	function cellColor($cells,$color){
	    global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	             'rgb' => $color
	        )
	    ));
	}

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	/*--------------------------------------CREATE SHEET FOR B2B START-------------------------------------*/
		
			$objPHPExcel->setActiveSheetIndex(0);
			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For B2B(4)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of Receipients');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Total Invoice Value');
			$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Total Cess');

			
			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'GSTIN/UIN Of Receipient');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Receiver Name');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Invoice Number');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Invoice Date');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Invoice Value');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Reverse Charge');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Applicable Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Invoice Type');		
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'E-Commerce GSTIN');		
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Taxable Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Cess Amount');
			
			if(isset($data['B2B']) && !empty($data['B2B'])){
			    $objPHPExcel->getActiveSheet()->getStyle("E3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("L3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("M3")->getNumberFormat()->setFormatCode('0.00');
			    
			    $objPHPExcel->getActiveSheet()->setCellValue('A3', (isset($data['B2B']['total_recipients'])) ? $data['B2B']['total_recipients'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('C3', (isset($data['B2B']['no_of_invoice'])) ? $data['B2B']['no_of_invoice'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('E3', (isset($data['B2B']['total_invoice_value']) && $data['B2B']['total_invoice_value'] != '') ? $data['B2B']['total_invoice_value'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('L3', (isset($data['B2B']['total_taxable_value']) && $data['B2B']['total_taxable_value'] != '') ? $data['B2B']['total_taxable_value'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('M3', (isset($data['B2B']['total_cess']) && $data['B2B']['total_cess'] != '') ? $data['B2B']['total_cess'] : 0);
			    
			    if(!empty($data['B2B']['data'])){
			        $row = 5;
			        foreach($data['B2B']['data'] as $key => $value){
			            $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('0.00');
			            
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, (isset($value['gstin'])) ? $value['gstin'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, (isset($value['receiver_name'])) ? $value['receiver_name'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, (isset($value['invoice_no'])) ? $value['invoice_no'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d-M-y',strtotime($value['invoice_date'])) : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, (isset($value['invoice_value']) && $value['invoice_value'] != '') ? $value['invoice_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, (isset($value['place_of_supply'])) ? $value['place_of_supply'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, (isset($value['reverse_charge'])) ? $value['reverse_charge'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, (isset($value['applicable_tax'])) ? $value['applicable_tax'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, (isset($value['invoice_type'])) ? $value['invoice_type'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, (isset($value['e_commerce_gstin'])) ? $value['e_commerce_gstin'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, (isset($value['taxable_value']) && $value['taxable_value'] != '') ? $value['taxable_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, (isset($value['cess']) && $value['cess'] != '') ? $value['cess'] : 0);
			            $row++;
			        }
			        unset($row);
			    }
			}


			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('B2B');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:M4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');
			cellColor('A2:M2', '0070c0');
			cellColor('A4:M4', 'f7caac');

			// setcolor
			$stylefont = array('font' => array('size' => 10,'bold' => true,'color' => array('rgb' => 'ffffff')));
			$tableHeader = array('font' => array('size' => 10,'bold' => true));
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($stylefont);
			$objPHPExcel->getActiveSheet()->getStyle('A1:M2')->applyFromArray($stylefont);
			$objPHPExcel->getActiveSheet()->getStyle('A4:M4')->applyFromArray($tableHeader);
			// $objPHPExcel->getActiveSheet()->getStyle('A4:M4')->applyFromArray($stylefont);
		
	/*------------------------------------------CREATE SHEET FOR B2B END-----------------------------------------------------------*/

	/*------------------------------------------CREATE SHEET FOR B2BA START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(1);
			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For B2BA');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('E1:O1');
			$objPHPExcel->getActiveSheet()->getStyle('E1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of Receipients');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Total Invoice Value');
			$objPHPExcel->getActiveSheet()->setCellValue('N2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('O2', 'Total Cess');

			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('A3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('C3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('G3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('M3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('N3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('O3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'GSTIN/UIN Of Receipient');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Receiver Name');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Invoice Number');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Origional Invoice Date');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Revised Invoice Number');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Revised Invoice Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Invoice Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Place Of Supply');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Reverse Charge');		
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Applicable % Of Tax Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Invoice Type');	
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'E-Commerce GSTIN');		
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Taxable Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('O4', 'Cess Amount');	




			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('B2BA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:O4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:D1', 'f7caac');//orange
			cellColor('E1:O1', 'b4c6e7');
			cellColor('A2:O2', '0070c0');
			cellColor('A4:D4', 'f7caac');
			cellColor('E4:O4', 'b4c6e7');

			// $stylefont = array('font' => array('size' => 10,'bold' => true,'color' => array('rgb' => 'ffffff')));
			// $tableHeader = array('font' => array('size' => 10,'bold' => true));
			$objPHPExcel->getActiveSheet()->getStyle('A1:O4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*----------------------------------------------CREATE SHEET FOR B2BA END------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR B2CL START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(2);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For B2CL(5)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Total Inv Value');
			$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Total Cess');

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Invoices Number');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Invoice Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Invoice Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Place Of Supply');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Applicable % Of Tax Rate');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Taxable Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Cess Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'E-Commerce GSTIN');
            
            if(isset($data['B2CL']) && !empty($data['B2CL'])){
                
                $objPHPExcel->getActiveSheet()->getStyle("C3")->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("G3")->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("H3")->getNumberFormat()->setFormatCode('0.00');
			    
			    $objPHPExcel->getActiveSheet()->setCellValue('A3', (isset($data['B2CL']['no_of_invoice'])) ? $data['B2CL']['no_of_invoice'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('C3', (isset($data['B2CL']['total_invoice_value']) && $data['B2CL']['total_invoice_value'] != '') ? $data['B2CL']['total_invoice_value'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('G3', (isset($data['B2CL']['total_taxable_value']) && $data['B2CL']['total_taxable_value'] != '') ? $data['B2CL']['total_taxable_value'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('H3', (isset($data['B2CL']['total_cess']) && $data['B2CL']['total_cess'] != '') ? $data['B2CL']['total_cess'] : 0);
			    
			    if(!empty($data['B2CL']['data'])){
			        $row = 5;
			        foreach($data['B2CL']['data'] as $key => $value){
			            $objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0.00');
			            
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, (isset($value['invoice_no'])) ? $value['invoice_no'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d-M-y',strtotime($value['invoice_date'])) : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, (isset($value['invoice_value']) && $value['invoice_value'] != '') ? $value['invoice_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, (isset($value['place_of_supply'])) ? $value['place_of_supply'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, (isset($value['applicable_tax'])) ? $value['applicable_tax'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, (isset($value['taxable_value']) && $value['taxable_value'] != '') ? $value['taxable_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, (isset($value['cess']) && $value['cess'] != '') ? $value['cess'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, (isset($value['e_commerce_gstin'])) ? $value['e_commerce_gstin'] : '');
			            $row++;
			        }
			        unset($row);
			    }
			}
            
			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('B2CL');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:I4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:I2', '0070c0');
			cellColor('A4:I4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:I4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR B2CL END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR B2CLA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(3);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For B2CLA');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:C1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('D1:K1');
			$objPHPExcel->getActiveSheet()->getStyle('D1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Total Inv Value');
			$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Total Cess');

			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('A3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('F3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('I3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('J3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Origional Invoice Number');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Origional Invoice Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Revised Invoice Number');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Revised Invoice Date');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Invoice Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Applicable % Of Tax Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Taxable Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Cess Amount');		
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'E-Commerce GSTIN');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('B2CLA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:K4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:C1', 'f7caac');//orange
			cellColor('D1:K1', 'b4c6e7');//dark blue
			cellColor('A2:K2', '0070c0');
			cellColor('A4:C4', 'f7caac');
			cellColor('D4:K4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:K4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR B2CLA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR B2CS START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(4);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For B2CS(7)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Total Cess');
			

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Type');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Applicable % Of Tax Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Taxable Value');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Cess Amount');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'E-Commerce GSTIN');
            
            if(isset($data['B2CS']) && !empty($data['B2CS'])){
                
                $objPHPExcel->getActiveSheet()->getStyle("E3")->getNumberFormat()->setFormatCode('0.00');
                $objPHPExcel->getActiveSheet()->getStyle("F3")->getNumberFormat()->setFormatCode('0.00');
			    
			    $objPHPExcel->getActiveSheet()->setCellValue('E3', (isset($data['B2CS']['total_taxable_value']) && $data['B2CS']['total_taxable_value'] != '') ? $data['B2CS']['total_taxable_value'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('F3', (isset($data['B2CS']['total_cess']) && $data['B2CS']['total_cess'] != '') ? $data['B2CS']['total_cess'] : 0);
			    
			    if(!empty($data['B2CS']['data'])){
			        $row = 5;
			        foreach($data['B2CS']['data'] as $key => $value){
			            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0.00');
			            
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'OE');
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, (isset($value['place_of_supply'])) ? $value['place_of_supply'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, (isset($value['applicable_tax'])) ? $value['applicable_tax'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, (isset($value['taxable_value']) && $value['taxable_value'] != '') ? $value['taxable_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, (isset($value['cess']) && $value['cess'] != '') ? $value['cess'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, (isset($value['e_commerce_gstin'])) ? $value['e_commerce_gstin'] : '');
			            $row++;
			        }
			        unset($row);
			    }
			}
            
			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('B2CS');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:G4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:G2', '0070c0');
			cellColor('A4:G4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:G4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR B2CS END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR B2CSA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(5);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For B2CSA');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('C1:I1');
			$objPHPExcel->getActiveSheet()->getStyle('C1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Total Cess');

			// set third column
// 			$objPHPExcel->getActiveSheet()->setCellValue('G3', 0);
// 			$objPHPExcel->getActiveSheet()->setCellValue('H3', 0);

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Financial Year');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Origional Month');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Type');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Applicable % Of Tax Rate');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Taxable Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Cess Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'E-Commerce GSTIN');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('B2CSA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:I4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1', 'f7caac');//orange
			cellColor('C1:I1', 'b4c6e7');//dark blue
			cellColor('A2:I2', '0070c0');
			cellColor('A4:B4', 'f7caac');
			cellColor('C4:I4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:I4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR B2CSA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR CDNR START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(6);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For CDNR(9B)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of Receipient');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'No. Of Notes/Voucher');
			$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Total Note/Refund Voucher Value');
			$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Total Cess');

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'GSTIN/UIN Of Receipient');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Receiver Name');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Invoices/Advance Receipt No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Invoices/Advance Receipt Date');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Note/Refund Voucher No.');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Note/Refund Voucher Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Document Type');
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Place Of Supply');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Note/Refund Voucher Value');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Cess Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Pre GST');
			
			if(isset($data['CDNR']) && !empty($data['CDNR'])){
			    
			    $objPHPExcel->getActiveSheet()->getStyle("I3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("L3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("M3")->getNumberFormat()->setFormatCode('0.00');
			    
			    // set third column
    			$objPHPExcel->getActiveSheet()->setCellValue('A3', (isset($data['CDNR']['total_recipients']) && $data['CDNR']['total_recipients'] != '') ? $data['CDNR']['total_recipients'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('C3', (isset($data['CDNR']['no_of_invoice']) && $data['CDNR']['no_of_invoice'] != '') ? $data['CDNR']['no_of_invoice'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('E3', (isset($data['CDNR']['no_of_voucher']) && $data['CDNR']['no_of_voucher'] != '') ? $data['CDNR']['no_of_voucher'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('I3', (isset($data['CDNR']['total_voucher_value']) && $data['CDNR']['total_voucher_value'] != '') ? $data['CDNR']['total_voucher_value'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('L3', (isset($data['CDNR']['total_taxable_value']) && $data['CDNR']['total_taxable_value'] != '') ? $data['CDNR']['total_taxable_value'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('M3', (isset($data['CDNR']['total_cess']) && $data['CDNR']['total_cess'] != '') ? $data['CDNR']['total_cess'] : 0);
			    
			    if(!empty($data['CDNR']['data'])){
			        $row = 5;
			        foreach($data['CDNR']['data'] as $key => $value){
			            $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('0.00');
			            
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, (isset($value['gstin'])) ? $value['gstin'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, (isset($value['receiver_name'])) ? $value['receiver_name'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, (isset($value['invoice_no'])) ? $value['invoice_no'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d-M-y',strtotime($value['invoice_date'])) : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, (isset($value['voucher_no'])) ? $value['voucher_no'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, (isset($value['voucher_date']) && $value['voucher_date'] != '' && $value['voucher_date'] != '0000-00-00') ? date('d-M-y',strtotime($value['voucher_date'])) : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, (isset($value['doc_type'])) ? $value['doc_type'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, (isset($value['place_of_supply'])) ? $value['place_of_supply'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, (isset($value['voucher_value']) && $value['voucher_value'] != '') ? $value['voucher_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, (isset($value['applicable_tax'])) ? $value['applicable_tax'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, (isset($value['taxable_value']) && $value['taxable_value'] != '') ? $value['taxable_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, (isset($value['cess']) && $value['cess'] != '') ? $value['cess'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, (isset($value['pre_gst'])) ? $value['pre_gst'] : '');
			            $row++;
			        }
			        unset($row);
			    }
			}

			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('CDNR');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:N2', '0070c0');
			cellColor('A4:N4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:N4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR CDNR END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR CDNRA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(7);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For CDNRA');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:F1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('G1:P1');
			$objPHPExcel->getActiveSheet()->getStyle('G1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of Receipients');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'No. Of Notes/Vouchers');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Total Note/Refund Voucher Value');
			$objPHPExcel->getActiveSheet()->setCellValue('N2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('O2', 'Total Cess');

			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('A3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('C3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('K3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('N3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('O3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'GSTIN/UIN Of Receipient');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Receiver Name');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Note/Refund Voucher No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Origional Note/Refund Voucher Date');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Origional Invoice/Advance Receipt No.');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Origional Invoice/Advance Receipt Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Revised Note/Refund Voucher No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Revised Note/Refund Voucher Date');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Document Type');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Supply Type');
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Note/Refund Voucher Value');
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('O4', 'Cess Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('P4', 'PRE GST');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('CDNRA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:P4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:F1', 'f7caac');//orange
			cellColor('G1:P1', 'b4c6e7');//dark blue
			cellColor('A2:P2', '0070c0');
			cellColor('A4:F4', 'f7caac');
			cellColor('G4:P4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:P4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:P2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR CDNRA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR CDNUR START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(8);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For CDNUR(9B)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('B2', 'No. Of Notes/Voucher');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Total Notes Value');
			$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Total Cess');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('B3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('H3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('K3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('L3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'UR Type');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Note/Refund Voucher No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Note/Refund Voucher Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Document Type');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Invoice/Advance Receipt No.');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Invoice/Advance Receipt Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Place Of Supply');
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Note/Refund Voucher Value');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Cess Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Pre GST');
			
			if(isset($data['CDNUR']) && !empty($data['CDNUR'])){
			    
			    $objPHPExcel->getActiveSheet()->getStyle("H3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("K3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("L3")->getNumberFormat()->setFormatCode('0.00');
			    
			    $objPHPExcel->getActiveSheet()->setCellValue('B3', (isset($data['CDNUR']['no_of_voucher']) && $data['CDNUR']['no_of_voucher'] != '') ? $data['CDNUR']['no_of_voucher'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('E3', (isset($data['CDNUR']['no_of_invoice']) && $data['CDNUR']['no_of_invoice'] != '') ? $data['CDNUR']['no_of_invoice'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('H3', (isset($data['CDNUR']['total_voucher_value']) && $data['CDNUR']['total_voucher_value'] != '') ? $data['CDNUR']['total_voucher_value'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('K3', (isset($data['CDNUR']['total_taxable_value']) && $data['CDNUR']['total_taxable_value'] != '') ? $data['CDNUR']['total_taxable_value'] : 0);
    			$objPHPExcel->getActiveSheet()->setCellValue('L3', (isset($data['CDNUR']['total_cess']) && $data['CDNUR']['total_cess'] != '') ? $data['CDNUR']['total_cess'] : 0);
			    
			    
			    if(!empty($data['CDNUR']['data'])){
			        $row = 5;
			        foreach($data['CDNUR']['data'] as $key => $value){
			            $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('0.00');
			            
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'B2CL');
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, (isset($value['voucher_no'])) ? $value['voucher_no'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, (isset($value['voucher_date']) && $value['voucher_date'] != '' && $value['voucher_date'] != '0000-00-00') ? date('d-M-y',strtotime($value['voucher_date'])) : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, (isset($value['doc_type'])) ? $value['doc_type'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, (isset($value['invoice_no'])) ? $value['invoice_no'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d-M-y',strtotime($value['invoice_date'])) : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, (isset($value['place_of_supply'])) ? $value['place_of_supply'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, (isset($value['voucher_value']) && $value['voucher_value'] != '') ? $value['voucher_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, (isset($value['applicable_tax'])) ? $value['applicable_tax'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, (isset($value['taxable_value']) && $value['taxable_value'] != '') ? $value['taxable_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, (isset($value['cess']) && $value['cess'] != '') ? $value['cess'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, (isset($value['pre_gst'])) ? $value['pre_gst'] : '');
			            $row++;
			        }
			        unset($row);
			    }
			}

			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('CDNUR');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:M4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:M2', '0070c0');
			cellColor('A4:M4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:M4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR CDNUR END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR CDNURA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(9);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For CDNURA');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('F1:O1');
			$objPHPExcel->getActiveSheet()->getStyle('F1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('B2', 'No. Of Notes/Vouchers');
			$objPHPExcel->getActiveSheet()->setCellValue('D2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Total Note Value');
			$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('N2', 'Total Cess');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('B3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('D3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('J3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('M3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('N3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'UR Type');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Origional Note/Refund Voucher No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Note/Refund Voucher Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Origional Invoice/Advance Receipt No.');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Origional Invoice/Advance Receipt Date');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Revised Note/Refund Voucher No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Revised Note/Refund Voucher Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Document Type');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Supply Type');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Note/Refund Voucher Value');
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Applicable % Of Tax Rat');
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Cess Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('O4', 'PRE GST');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('CDNURA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:O4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:E1', 'f7caac');//orange
			cellColor('F1:O1', 'b4c6e7');//dark blue
			cellColor('A2:O2', '0070c0');
			cellColor('A4:E4', 'f7caac');
			cellColor('F4:O4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:O4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR CDNURA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR EXP START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(10);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For EXP(6)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('B2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Total Invoices Value');
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'No. Of Shipping Bill');
			$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Total Taxable Value');
			// set third column
// 			$objPHPExcel->getActiveSheet()->setCellValue('B3', 0);
// 			$objPHPExcel->getActiveSheet()->setCellValue('D3', 0);
// 			$objPHPExcel->getActiveSheet()->setCellValue('F3', 0);
// 			$objPHPExcel->getActiveSheet()->setCellValue('K3', 0);

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Export Type');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Invoice No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Invoice Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Invoice Value');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Port Code');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Shipping Bill No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Shipping Bill Date');
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Cess Amount');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('EXP');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:K4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:K2', '0070c0');
			cellColor('A4:K4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:K4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR EXP END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR EXPA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(11);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For EXPA');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:C1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('D1:M1');
			$objPHPExcel->getActiveSheet()->getStyle('D1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('B2', 'No. Of Invoices');
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Total Invoice Value');
			$objPHPExcel->getActiveSheet()->setCellValue('H2', 'No. Of Shipping Bill');
			$objPHPExcel->getActiveSheet()->setCellValue('M2', 'Total Taxable Value');
			// set third column
		/*	$objPHPExcel->getActiveSheet()->setCellValue('B3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('F3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('H3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('M3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Export Type');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Origional Invoice No.');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Invoice Date');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Revised Invoice No.');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Revised Invoice Date');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Invoice Value');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Port Code');		
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Shipping Bill No.');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Shipping Bill Date');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Cess Amount');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('EXPA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:M4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:C1', 'f7caac');//orange
			cellColor('D1:M1', 'b4c6e7');//dark blue
			cellColor('A2:M2', '0070c0');
			cellColor('A4:C4', 'f7caac');
			cellColor('D4:M4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:M4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR EXPA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR AT START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(12);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For Advance Received(11B)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Total Advance Received');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Total Cess');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('D3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Applicable % Of Tax Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Gross Advance Received');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Cess Amount');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('AT');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:E2', '0070c0');
			cellColor('A4:E4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR AT END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR ATA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(13);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For Amended Tax Liability(Advance Received)');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:C1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('D1:G1');
			$objPHPExcel->getActiveSheet()->getStyle('D1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Total Advance Received');
			$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Total Cess');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('F3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('G3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Financial Year');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Origional Month');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Rate');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Gross Advance Received');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Cess Amount');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('ATA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:G4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:C1', 'f7caac');//orange
			cellColor('D1:G1', 'b4c6e7');//dark blue
			cellColor('A2:G2', '0070c0');
			cellColor('A4:C4', 'f7caac');
			cellColor('D4:G4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:G4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR ATA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR ATADJ START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(14);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For Advance Adujested(11B)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Total Advance Adujested');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Total Cess');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('D3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Applicable % Of Tax Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Rate');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Gross Advance Adujested');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Cess Amount');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('ATADJ');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:E2', '0070c0');
			cellColor('A4:E4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR ATADJ END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR ATADJA START-----------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(15);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For Amendement Of Adjustment Advances');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Origional Detail');
			$objPHPExcel->getActiveSheet()->mergeCells('B1:C1');
			$objPHPExcel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Revised Details');
			$objPHPExcel->getActiveSheet()->mergeCells('D1:G1');
			$objPHPExcel->getActiveSheet()->getStyle('D1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Total Advance Received');
			$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Total Cess');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('F3', 0);
			$objPHPExcel->getActiveSheet()->setCellValue('G3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Financial Year');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Origional Month');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Origional Place Of Supply');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Applicable % Of Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Rate');	
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Gross Advance Adujested');		
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Cess Amount');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('ATADJA');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:G4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('B1:C1', 'f7caac');//orange
			cellColor('D1:G1', 'b4c6e7');//dark blue
			cellColor('A2:G2', '0070c0');
			cellColor('A4:C4', 'f7caac');
			cellColor('D4:G4', 'b4c6e7');

			$objPHPExcel->getActiveSheet()->getStyle('A1:G4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR ATADJA END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR EXEMP START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(16);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For Nil Rated, Exempted and non GST Outward Supplies(8)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Total Nil Rated Supplies');
			$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Total Exempted Supplies');
			$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Total Non-Gst Supplies');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('B3', 0.00);
			$objPHPExcel->getActiveSheet()->setCellValue('C3', 0.00);
			$objPHPExcel->getActiveSheet()->setCellValue('D3', 0.00);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Description');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Nil Rated Supplies');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Exempted (Other Than Nil Rated / Non GST Supplies)');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Non GST Supplies');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('EXEMP');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:D4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:D2', '0070c0');
			cellColor('A4:D4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:D4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR EXEMP END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR HSN START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(17);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For HSN(12)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No. Of HSN');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Total Value');
			$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Total Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Total Integerated Value');
			$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Total Central Tax');
			$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Total State/UT Tax');
			$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Total Cess');
			

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'HSN');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Description');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'UQC');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Total Quentity');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Total Value');
			$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Taxable Value');
			$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Integerated Tax Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Central Tax Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('I4', 'State/UT Tax Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Cess Amount');
			
			if(isset($data['HSN']) && !empty($data['HSN'])){
			    
			    $objPHPExcel->getActiveSheet()->getStyle("E3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("F3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("G3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("H3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("I3")->getNumberFormat()->setFormatCode('0.00');
			    $objPHPExcel->getActiveSheet()->getStyle("J3")->getNumberFormat()->setFormatCode('0.00');
			    
			    
			    $objPHPExcel->getActiveSheet()->setCellValue('A3', (isset($data['HSN']['no_of_hsn'])) ? $data['HSN']['no_of_hsn'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('E3', (isset($data['HSN']['total_value']) && $data['HSN']['total_value'] != '') ? $data['HSN']['total_value'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('F3', (isset($data['HSN']['total_taxable']) && $data['HSN']['total_taxable'] != '') ? $data['HSN']['total_taxable'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('G3', (isset($data['HSN']['total_igst']) && $data['HSN']['total_igst'] != '') ? $data['HSN']['total_igst'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('H3', (isset($data['HSN']['total_cgst']) && $data['HSN']['total_cgst'] != '') ? $data['HSN']['total_cgst'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('I3', (isset($data['HSN']['total_sgst']) && $data['HSN']['total_sgst'] != '') ? $data['HSN']['total_sgst'] : 0);
			    $objPHPExcel->getActiveSheet()->setCellValue('J3', (isset($data['HSN']['total_cess']) && $data['HSN']['total_cess'] != '') ? $data['HSN']['total_cess'] : 0);
			    
			    if(!empty($data['HSN']['data'])){
			        $row = 5;
			        foreach($data['HSN']['data'] as $key => $value){
			            $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('0.00');
			            $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0.00');
			            
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, (isset($value['hsn'])) ? $value['hsn'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, (isset($value['desc'])) ? $value['desc'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, (isset($value['uqc'])) ? $value['uqc'] : '');
			            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, (isset($value['total_qty']) && $value['total_qty'] != '') ? $value['total_qty'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, (isset($value['total_value']) && $value['total_value'] != '') ? $value['total_value'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, (isset($value['total_taxable']) && $value['total_taxable'] != '') ? $value['total_taxable'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, (isset($value['total_igst']) && $value['total_igst'] != '') ? $value['total_igst'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, (isset($value['total_cgst']) && $value['total_cgst'] != '') ? $value['total_cgst'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, (isset($value['total_sgst']) && $value['total_sgst'] != '') ? $value['total_sgst'] : 0);
			            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, (isset($value['total_cess']) && $value['total_cess'] != '') ? $value['total_cess'] : 0);
			            $row++;
			        }
			        unset($row);
			    }
			}

			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('HSN');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:J4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:J2', '0070c0');
			cellColor('A4:J4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:J4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR HSN END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR DOCS START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(18);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Summary For Documents Issued During The Tax Period(13)');

			// set second column
			$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Total Number');
			$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Total Cancelled');
			// set third column
			/*$objPHPExcel->getActiveSheet()->setCellValue('D3', 349);
			$objPHPExcel->getActiveSheet()->setCellValue('E3', 0);*/

			// set fourth column for table header
			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Nature Of Document');		
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Sr. No. From');		
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Sr. No. To');		
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Total Number');
			$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Total Cancelled');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('DOCS');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1', '0070c0');//blue
			cellColor('A2:E2', '0070c0');
			cellColor('A4:E4', 'f7caac');//orange

			$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray(['font' => ['color' => ['rgb' => 'ffffff']]]);
		
	/*---------------------------------------------CREATE SHEET FOR DOCS END-------------------------------------------------------*/

	/*---------------------------------------------CREATE SHEET FOR MASTER START-------------------------------------------------------*/
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(19);

			// set first column
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'UQC');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Export Type');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Reverse Charge/Provisional Assessment');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Note Type');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Type');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tax Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', 'POS');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Invoice Type');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Reason For Issuing Note');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Nature Of Document');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', 'UR Type');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Supply Type');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Month');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Financial Year');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', 'differential Percentage');

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('MASTER');

			// set border
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);
			unset($styleArray);

			// set auto adjust all cell
			for ($i = 'A'; $i !=  $objPHPExcel->getActiveSheet()->getHighestColumn(); $i++) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
			}

			// set cell color
			cellColor('A1:O1', 'b4c6e7');//dark blue

			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(['font' => ['size' => 10, 'bold' => true]]);
		

	/*---------------------------------------------CREATE SHEET FOR MASTER END-------------------------------------------------------*/

	// Redirect output to a client’s web browser (Excel5)
	$date = 'Pharmacy_Tax_Summary_'.date('d_m_Y_H_i');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$date.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>
