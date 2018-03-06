<?php

	$target = esc( $_GET['target'] );
	$code = esc( $_GET['code'] );
		
	$taskManager = new Task();
	$task = $taskManager->getTaskDetail( $target, $code );
	$task->department = $taskManager->getDepartment( $target );
	$task->staff = $taskManager->getStaff($target, $code);

	$encodedFileDir = UPLOAD_DIR . rawurlencode( $task->department->name ) . '/' . $target . '/' . $code;
	$fileDir = UPLOAD_DIR . $task->department->name . '/' . $target . '/' . $code;

?> 

<form method="post" enctype="multipart/form-data" action="" >
	<div class='noprint flex flex_row padding_bottom space_between wrap' style="min-height: 35px;">
		<div class="bigger_font">
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=target&department=" . $task->department->ID ); ?>&target=<?php echo $target;?>" title="<?php echo TARGETS_BY_DEPARTMENT; ?>"><?php echo esc( $task->department->name ); ?></a>
			<span> >> </span>
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=task&department=" . $task->department->ID ); ?>&target=<?php echo $target;?>" title="<?php echo TASKS_BY_TARGET; ?>"><?php echo $target; ?></a>
		</div>
	</div>
	<div class='indicator_edit flex flex_column shadow round_border space_between light_grey_border border padding'>
		<div class="flex flex_column padding">
			<label>Servizio</label>
			<input class='padding' type="text" name="departmentName" id="task" value="<?php echo esc( $task->department->name ); ?>" disabled />
			<input type="hidden" name="departmentName" value="<?php echo esc( $task->department->name ); ?>" />
			<input type="hidden" name="department" value="<?php echo esc( $task->department->ID ); ?>" />
		</div>
		<div class="flex flex_column fair_grow padding">
			<label>Attività</label>
			<input class='padding' type="text" name="task" id="task" value="<?php echo esc( $task->target . $task->code ); ?>" disabled />
		</div>
		<div class="flex flex_column padding">
			<label>Descrizione</label>
			<div class='full_grow padding disabledInput'><?php echo htmlentities(  preg_replace( '/\r|\n/', '', $task->description ), ENT_QUOTES ); ?></div>
			<input type='hidden' name="description" value="<?php echo htmlentities(  preg_replace( '/\r|\n/', '', $task->description ), ENT_QUOTES ); ?>" />
		</div>
		<div class="flex flex_row wrap">
			<div class="flex flex_column nowrap align_left fair_grow">			
				<div class="flex flex_row padding wrap">
					<div class="flex flex_column fair_grow small_right_margin">
						<label>Data di inizio attività</label>
						<input class='padding' type="date" id="startDate" value="<?php echo esc( $task->startDate ); ?>" disabled />
						<input type='hidden' name="startDate" value="<?php echo esc( $task->startDate ); ?>" />
					</div>	
					<div class="flex flex_column fair_grow small_right_margin">
						<label>Data di fine attività</label>
						<input class='padding' name="endDate" type="date" id="endDate" value="<?php echo esc( $task->endDate ); ?>" required />
					</div>		
				</div>	
				<div class="flex flex_row padding wrap">
					<div class="flex flex_column fair_grow small_right_margin">
						<label>Risultato atteso (%)</label>
						<input class='padding' type="text" id="attendedResult" value="<?php echo esc( $task->attendedResult ); ?>%" disabled />
						<input type='hidden' name="attendedResult" value="<?php echo esc( $task->attendedResult ); ?>" />
					</div>
					<div class="flex flex_column fair_grow small_right_margin">		
						<label>Risultato raggiunto (%)</label>
						<input class='padding' type="text" name="actualResult" id="actualResult" value="<?php echo esc( $task->actualResult ); ?>" required />
					</div>
					<div class="flex flex_column fair_grow small_right_margin">
						<label>Progressione (%)</label>
						<input class='padding' type="text" name="progression" id="progression" value="<?php echo $task->progression == '100' ? "COMPLETO" : esc( $task->progression ); ?>" required />
					</div>
				</div>
				<div class='flex flex_column padding small_right_margin'>
					<label class='fair_grow middle_blue_focus'>Descrizione dell'attività sostenuta</label>
					<textarea class='padding autoExpand' name="difficulty" id="difficulty" required /><?php echo esc( $task->difficulty ); ?></textarea>
				</div>
			</div>
			<div class="flex flex_column nowrap align_left fair_grow">	
				<div class="flex flex_column padding control">
					<label>Personale impiegato</label>
					<?php if( $task->staff ) { 
						foreach ( $task->staff as $staff ) { ?>
							<div id="field" class="flex flex_row no_wrap entry input-group" style="margin-bottom: 15px">
								<input autocomplete="off" class="form-control padding full_width" id="field1"  name="staff[]" type="text" placeholder="Cognome Nome" data-items="8" value="<?php echo $staff->user; ?>"/>
								<span class="input-group-btn">
									<button id="b1" class="btn btn-success btn-add white_text" type="button">
										<i class="material-icons">&#xE145;</i>
									</button>
								</span>
							</div>
						<?php  } ?>
					<?php  } ?>
						<div id="field" class="flex flex_row no_wrap entry input-group" style="margin-bottom: 15px">
							<input autocomplete="off" class="form-control padding full_width" id="field1"  name="staff[]" type="text" placeholder="Cognome Nome" data-items="8" value=""/>
							<span class="input-group-btn">
								<button id="b1" class="btn btn-success btn-add white_text" type="button">
									<i class="material-icons">&#xE145;</i>
								</button>
							</span>
						</div>
				</div>
			</div>
		</div>
		<div class='flex flex_column align_left space_between padding'>
			<label class='fair_grow'>Allegati</label>
			<?php 
				$attachments = FALSE;
				if( file_exists( $fileDir ) ) {
					$files = scandir( $fileDir );
					foreach ( $files as $file ) { 
						if ($file == '.' || $file == '..') continue; 
						$attachments = TRUE;
						?>
						<div class='flex flex_row light_grey_background'>
							<div class='full_grow light_blue_background_hover transition padding'><a class='block' href="<?php echo $encodedFileDir . '/' . rawurlencode ( $file ); ?>" title="Leggi il documento"><i class="material-icons align_middle zoom small_right_margin">&#xE8FF;</i><?php echo $file; ?></a></div>
							<div class="flex flex_column align_middle align_center padding"><a class='red_text_hover block zoom' href="<?php echo htmlspecialchars( $_SERVER['REQUEST_URI'].'&action=deleteTaskAttachment&file=' ) . rawurlencode ( $file ); ?>" title="Elimina il documento"><i class="material-icons align_middle">&#xE14C;</i></a></div>
						</div>
					<?php							
					}
				}
				if( !$attachments ) { 
				?>
				<div class='full_grow padding'>Non sono presenti allegati</div>
			<?php } ?>
		</div>
		<div class="padding middle_red_text">(Max 10Mb totali)</div>	
		<input type="file" name="file[]" id="file" accept=".pdf" class="padding" multiple />
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('indicator', 'edit'); ?>
		</div>
	</div>
</form>