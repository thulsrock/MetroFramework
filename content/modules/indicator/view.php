<?php

	$target = esc( $_GET['target'] );
	$code = esc( $_GET['code'] );
		
	$taskManager = new Task();
	$task = $taskManager->getTaskDetail( $target, $code );
	$taskStaff = $taskManager->getStaff($target, $code);
	$task->department = $taskManager->getDepartment( $target );
	
	$encodedFileDir = UPLOAD_DIR . rawurlencode( $task->department->name ) . DIRECTORY_SEPARATOR . $target . DIRECTORY_SEPARATOR . $code;
	$encodedReportDir = REPORT_DIR . CURRENT_YEAR . DIRECTORY_SEPARATOR . rawurlencode( $task->department->name ) . DIRECTORY_SEPARATOR . $target;
	$fileDir = UPLOAD_DIR . $task->department->name . DIRECTORY_SEPARATOR . $target . DIRECTORY_SEPARATOR . $code;
	$sheetFilename = rawurlencode( $target . $code .'.pdf' );
	
	$PDFSheetPath = $encodedFileDir . DIRECTORY_SEPARATOR . $sheetFilename;
	$PDFReportPath = $encodedReportDir . DIRECTORY_SEPARATOR . rawurlencode( $target . $code .'-report.pdf' );
 
?> 

	<div class='noprint flex flex_row padding_bottom space_between' style="height: 35px;">
		<div class="bigger_font">
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=target&department=" . $task->department->ID ); ?>&target=<?php echo $target;?>" title="<?php echo TARGETS_BY_DEPARTMENT; ?>"><?php echo esc( $task->department->name ); ?></a>
			<span> >> </span>
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=task&department=" . $task->department->ID ); ?>&target=<?php echo $target;?>" title="<?php echo TASKS_BY_TARGET; ?>"><?php echo $target; ?></a>
		</div>
		<div class="flex flex_row align_middle">
			
				<a id="button" class='border round_border zoom padding' href='#' title="Stampa scheda indicatore"  onclick="printThis()" >
					<i class="material-icons">print</i>
				</a>

				<a id="button" class='zoom' href='<?php echo $PDFSheetPath; ?>' style="margin-right: 5px;">
					<img style="height:37px; width: auto;" src="<?php echo PDF_TASK_LOGO; ?>" title="PDF scheda indicatore" />
				</a>

				<a id="button" class='zoom' href='<?php echo $PDFReportPath; ?>' style="margin-right: 5px;">
					<img style="height:37px; width: auto;" src="<?php echo PDF_TASK_MULTIPLE_LOGO; ?>" title="PDF scheda indicatore con allegati" />
				</a>
		</div>
	</div>

	
 	

<?php ob_start(); ?>

<div class='indicator_view flex flex_column shadow round_border space_between nowrap light_grey_border border padding'>
	<div class="flex flex_column padding">
		<label>Servizio</label>
		<div class='padding justify'><?php echo esc( $task->department->name ); ?></div>
	</div>
	<div class="flex flex_column padding">
		<label>Attività</label>
		<div class='padding justify border_light'><?php echo esc( $task->target . ' - ' . $task->code ); ?></div>
	</div>
	<div class="flex flex_column padding">
		<label>Descrizione</label>
		<div class='padding justify'><?php echo htmlentities(  preg_replace( '/\r|\n/', '', $task->description ), ENT_QUOTES ); ?></div>
	</div>
	
	<div class="flex flex_row wrap">
		<div class="flex flex_column fair_grow small_right_margin padding">
			<label>Inizio attività</label>
			<div class='padding align_center'><?php echo esc( $task->startDate ); ?></div>
		</div>
		<div class="flex flex_column fair_grow padding">
			<label>Fine attività</label>
			<div class='padding align_center'><?php echo esc( $task->endDate ); ?></div>
		</div>
		<div class="flex flex_column fair_grow small_right_margin padding">
			<label>Risultato atteso</label>
			<div class='padding align_center'><?php echo esc( $task->attendedResult ); ?> %</div>
		</div>
		<?php $actualResult = isset( $task->actualResult ) ? $task->actualResult : NULL; ?>
		<div class="flex flex_column fair_grow small_right_margin padding">	
			<label>Risultato raggiunto</label>
			<div class='padding align_center'><?php echo esc( $actualResult ); ?> %</div>
		</div>
		<div class="flex flex_column fair_grow padding">	
			<label>Progressione</label>
			<div class='padding align_center'><?php echo $task->progression == 100 ? "COMPLETO" : esc( $task->progression .' %' ); ?></div>
		</div>
	</div>

		<div class="flex flex_row">

			<div class="flex flex_column full_width">
				<div class="padding">
					<label>Attività sostenute e difficoltà riscontrate</label>
					<div class='padding justify'><?php echo $task->difficulty != '' ? esc( $task->difficulty ) : "Non indicata."?></div>
				</div>
				<div class="padding">
					<label>Allegati</label>
					<div class='flex flex_column'>
						<?php	
						$attachments = FALSE;
			
						if( file_exists( $fileDir ) ) {	
							$files = scandir( $fileDir );
							foreach ( $files as $file ) { 
								if ($file == '.' || $file == '..' || $file == $sheetFilename ) continue; 
								$attachments = TRUE;
								?>
								<div><a class='block padding middle_blue_text_hover' href=<?php echo $encodedFileDir . DIRECTORY_SEPARATOR . rawurlencode ( $file ); ?> title="Leggi il documento" target='_blank'><i class="material-icons align_middle noprint">&#xE8FF;</i><?php echo $file; ?></a></div>								
						<?php }
						}
						if( !$attachments ) { ?>
							<div class='full_grow padding'>Non sono presenti allegati</div>
						<?php } ?>
					</div>
				</div>
			</div>	
			<div class="flex flex_column wrap align_left" style="border-left: 1px solid var(--light_grey); flex-shrink:0; min-width: 25%;">	
				<div class="flex flex_column full_height padding">
					<label>Personale impiegato</label>
					<div class='full_height'>
					<?php
						if( $taskStaff ) { 
							foreach ( $taskStaff as $staff ) { ?>
								<div class='padding'><?php echo $staff->user; ?></div>
							<?php }
							} else { ?> <div class='padding'>Non individuato.</div>
					<?php } ?>	
						
					</div>
				</div>
			</div>
	</div>
</div>