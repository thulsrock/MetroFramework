<?php
	try {
		$targetManager = new Target();
		$target = $targetManager->getTargetDetailsFromCode( $_GET['target'] );
		$departmentManager = new DepartmentDAO();
		$department = $departmentManager->getNameFromID($target->department);
	} catch (Exception $e) {
		echo 'Non è possibile accedere ai dettagli dell\'obiettivo.';
		return;
	}
?>
<form method="post" enctype="multipart/form-data" action="">
	<div class='flex flex_row padding_bottom space_between' style="height: 35px;">
		<div class="bigger_font">
			<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=target&department=" . $target->department ); ?>" title="<?php echo TARGETS_BY_DEPARTMENT; ?>"><?php echo htmlentities( $department, ENT_QUOTES ); ?></a>
		</div>
	</div>
	<div class='target_edit flex flex_column shadow round_border space_between nowrap light_grey_border border padding'>
		<div class="flex flex_column padding">
			<label>Servizio</label>
			<input class='padding' type='text' value="<?php echo htmlentities( $department, ENT_QUOTES ); ?>" disabled />
			<input type='hidden' name="department" value="<?php echo htmlentities($target->department, ENT_QUOTES );?>" />
		</div>
		<div class="flex flex_column padding space_between">
			<div class="flex flex_row">
				<div class="flex flex_column small_right_margin">
					<label>Codice</label>
					<input class='padding' type='text' value="<?php echo htmlentities( $target->code, ENT_QUOTES ); ?>" disabled />
					<input type='hidden' name="code" value="<?php echo htmlentities( $target->code, ENT_QUOTES ); ?>" />
				</div>
				<div class="flex flex_column small_right_margin full_width">
					<label>Nome</label>
					<input class='padding' type="text" name="name" value="<?php echo htmlentities( $target->name, ENT_QUOTES ); ?>" required />
				</div>
				<div class="flex flex_column fair_grow">
					<label>Peso</label>
					<input class='padding' type="number" name="weight" value="<?php echo htmlentities( $target->weight, ENT_QUOTES ); ?>"  style="width: auto;" required >
				</div>
			</div>
		</div>
		<div class="flex flex_column padding space_between">
			<div class="flex flex_row">
				<div class="flex flex_column small_right_margin fair_grow">
					<label>Data di inizio</label>
					<input class='padding' style="width: auto;" type="date" name="startDate" value="<?php echo htmlentities( $target->startDate, ENT_QUOTES ); ?>" required />
				</div>	
				<div class="flex flex_column fair_grow">
					<label>Data di raggiungimento</label>
					<input class='padding' style="width: auto;" type="date" name="endDate" value="<?php echo htmlentities( $target->endDate, ENT_QUOTES ); ?>" required />
				</div>
			</div>
		</div>
		<div class="flex flex_column padding">
			<label>Descrizione</label>
			<textarea class="padding" rows="8" name="description" required><?php echo htmlentities( $target->description, ENT_QUOTES ); ?></textarea>
		</div>

		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('target','edit'); ?>
		</div>	
	</div>
</form>