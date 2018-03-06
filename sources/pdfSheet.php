<?php 

$people = array();
foreach ( $indicator['staff'] as $value ) {
	if( $value != '' ) $people[] = $value;
}
$indicator['staff'] = $people;

// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Francesco Peragine');
$pdf->SetAuthor('Ufficio Informatizzazione e Statistica');
$pdf->SetTitle('Città Metropolitana di Bari');
$pdf->SetSubject('Strumento di analisi per il Nucleo di Gestione');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, 5, 5);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage('P', 'A4');

$people = '';

if( !empty( $indicator['staff'] ) ) {
	foreach ( $indicator['staff'] as $staff ) {
		$people .= $staff .'<br />';
	}
} else $people .= "Non individuato.";
unset( $indicator['staff'] );

foreach( $indicator as $value ) {
	if (!is_array( $value ) ) {
		$value =  htmlspecialchars( $value, ENT_QUOTES );
	}
}
$logo = PDF_LOGO;

$html = '
<!DOCTYPE html>
<html>
	<head>
		<style>
			* {
				border: 0px;
				margin: 0px;
			}
			BODY {
				font-family: verdana;
				font-size: 12px;
				text-align: left;
			}
			TABLE {
				border-collapse: collapse;
			}
			th {
				color: #9cbbf3;
			}
			td {
				border-radius: 4px;
				line-height: 30px;
			}
		</style>	
		<title>Città Metropolitana di Bari</title>
	</head>
	
	<body>
		<table cellpadding="3" cellspacing="6">
			<tr>
				<th style="background-color: #fff; text-align: center; border-size: 0px;" colspan="5">
					<img align="center" alt="logo black" src="'.$logo.'" height=107 width=743 margin=0 padding=0/>
				</th>
			</tr>
			<tr>
				<th colspan="5"><b>Servizio</b></th>
			</tr>
			<tr>
				<td colspan="5">'.$indicator["departmentName"].'</td>
			</tr>
			<tr>
				<th colspan="5"><b>Attività</b></th>		
			</tr>
			<tr>
				<td colspan="5">'.$indicator["target"] . $indicator["code"].'</td>

			</tr>
			<tr>
				<th colspan="5"><b>Descrizione</b></th>
			</tr>
			<tr>
				<td colspan="5" style="text-align: justify;">'.$indicator["description"].'</td>
			</tr>
			<tr>
				<th><b>Data di inizio attività</b></th>
				<th><b>Data di fine attività</b></th>
				<th><b>Risultato atteso</b></th>
				<th><b>Risultato raggiunto</b></th>	
				<th><b>Progressione</b></th>	
			</tr>
			<tr>
				<td align="center">'.$indicator["startDate"].'</td>		
				<td align="center">'.$indicator["endDate"].'</td>
				<td align="center">'.$indicator["attendedResult"].' %</td>		
				<td align="center">'.$indicator["actualResult"].' %</td>
				<td align="center">'.$indicator["progression"].'</td>
						
			</tr>
			<tr>
				<th colspan="3"><b>Attività sostenute e difficotà riscontrate</b></th>
				<th colspan="2" style="border-left: 1px solid #e8e8e8;"><b>Personale impiegato</b></th>
			</tr>
			<tr>
				<td colspan="3">'.$indicator["difficulty"].'</td>
				<td colspan="2" rowspan="3" valign="top">'.$people.'</td>				
			</tr>
		</table>
	</body>
</html>';

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

$filepath =  UPLOAD_ROOT . $indicator['departmentName'] . DIRECTORY_SEPARATOR . $indicator['target'] . DIRECTORY_SEPARATOR . $indicator['code'] . DIRECTORY_SEPARATOR;
if ( !is_dir( $filepath ) ) {
	mkdir( $filepath, 0777, true);
}
$filename = $indicator['target'] . $indicator['code'] .'.pdf';
$fullPath = $filepath . DIRECTORY_SEPARATOR . $filename;
//Close and output PDF document
$pdf->Output( $filepath . $filename, 'F' );