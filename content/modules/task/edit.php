<?php

	$target = new Target();
	try {
		$targetList = $target->list();
	} catch (Exception $e) {
		echo 'Non sono presenti obiettivi.';
	}
	
	$task = new Task();
	try {
		$selectedTask = $task->getTaskDetail( $_GET['target'], $_GET['code']);
		$selectedTask->department = $task->getDepartment( $_GET['target'] );
	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>

<form method="post" enctype="multipart/form-data" action="">
	<div class='noprint flex flex_row padding_bottom space_between wrap' style="min-height: 35px;">
		<div class="bigger_font">
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=target&department=" . $selectedTask->department->ID ); ?>&target=<?php echo esc( $_GET['target'] );?>" title="<?php echo TARGETS_BY_DEPARTMENT; ?>"><?php echo esc( $selectedTask->department->name ); ?></a>
			<span> >> </span>
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=task&department=" . $selectedTask->department->ID ); ?>&target=<?php echo esc( $_GET['target'] );?>" title="<?php echo TASKS_BY_TARGET; ?>"><?php echo esc( $_GET['target'] ); ?></a>
		</div>
	</div>
	<div class='task_edit flex flex_column shadow round_border border padding'>
		<div class="flex flex_row padding">
			<div class="flex flex_column small_right_margin">
				<label>Obiettivo</label>
				<input type="text" class="padding" value="<?php echo htmlentities( $selectedTask->target, ENT_QUOTES );?>" disabled />
				<input type="hidden" name="target" value="<?php echo htmlentities( $selectedTask->target, ENT_QUOTES );?>" />
			</div>
			<div class="flex flex_column small_right_margin">
				<label>Codice attività</label>
				<input class="padding" type="text" name="newCode" value="<?php echo htmlentities( $selectedTask->code, ENT_QUOTES );?>" required />
				<input type="hidden" name="code" value="<?php echo htmlentities( $selectedTask->code, ENT_QUOTES );?>" />
			</div>
			<div class="flex flex_column full_width">
				<label>Nome</label>
				<input class="padding" type="text" name="name" value="<?php echo htmlentities( $selectedTask->name, ENT_QUOTES );?>" required />
			</div>
		</div>
		<div class="flex flex_column padding">
			<label>Descrizione</label>
			<textarea class='padding' name="description" rows="8" required ><?php echo htmlentities( $selectedTask->description, ENT_QUOTES );?></textarea>
		</div>
		<div class="flex flex_row padding">
			<div class="flex flex_column small_right_margin fair_grow">
				<label>Data di inizio</label>
				<input class='padding' style="width: auto;" type="date" name="startDate" value="<?php echo htmlentities( $selectedTask->startDate, ENT_QUOTES );?>" required />
			</div>	
			<div class="flex flex_column fair_grow small_right_margin">
				<label>Data di raggiungimento</label>
				<input class='padding' style="width: auto;" type="date" name="endDate" value="<?php echo htmlentities( $selectedTask->endDate, ENT_QUOTES );?>" required />
			</div>
			<div class="flex flex_column">
				<label>Risultato atteso</label>
				<input class='padding' style="width: auto;" type="number" name="attendedResult" value="<?php echo htmlentities( $selectedTask->attendedResult, ENT_QUOTES );?>" required />
			</div>
		</div>
			
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('task', 'edit'); ?>
		</div>	
	</div>
</form>