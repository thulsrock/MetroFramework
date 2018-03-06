<?php
	try {
		$department = new DepartmentDAO();
		$departmentList = $department->departmentList();
	} catch (Exception $e) {
		echo 'Non sono presenti istituti.';
		return;
	}
?>
<form method="post" enctype="multipart/form-data" action="">
	<div class='flex flex_column shadow round_border space_between nowrap light_grey_border border padding'>
		<div class="flex flex_column padding">
			<label class='middle_blue_text bold'>Servizio</label>
			<select class='border dark_blue_border_hover' name="department" id="department" required >
				<option class='padding' value=''>Selezionare un elemento</option>	
				<?php 
					foreach ( $departmentList as $department ) { ?>
						<option class='padding' value=<?php echo esc( $department->ID ); ?>><?php echo htmlentities( $department->name, ENT_QUOTES ); ?></option>	
				<?php } ?>		
			</select>
		</div>
		<div class="flex flex_column padding space_between">
			<div class="flex flex_row">
				<div class="flex flex_column small_right_margin">
					<label class='middle_blue_text bold'>Codice</label>
					<input class='padding' type="text" name="code" id="code" required />
				</div>
				<div class="flex flex_column small_right_margin full_width">
					<label class='middle_blue_text bold'>Nome</label>
					<input class='padding' type="text" name="name" id="name" required />
				</div>
				<div class="flex flex_column fair_grow">
					<label class='middle_blue_text bold'>Peso</label>
					<input class='padding' style="width: auto;" type="number" name="weight" id="weight" required >
				</div>
			</div>
		</div>
		<div class="flex flex_column padding">
			<label class='middle_blue_text bold'>Descrizione</label>
			<textarea class="padding" name="description" id="description" rows="5" required></textarea>
		</div>
		<div class="flex flex_column padding space_between">
			<div class="flex flex_row">
				<div class="flex flex_column small_right_margin fair_grow">
					<label class='middle_blue_text bold'>Data di inizio</label>
					<input class='padding' style="width: auto;" type="date" name="startDate" id="startDate" value="<?php echo esc( DEFAULT_START_DATE ); ?>" required />
				</div>	
				<div class="flex flex_column fair_grow">
					<label class='middle_blue_text bold'>Data di raggiungimento</label>
					<input class='padding' style="width: auto;" type="date" name="endDate" id="endDate" value="<?php echo esc( DEFAULT_END_DATE ); ?>" required />
				</div>
			</div>
		</div>
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('target','new'); ?>
		</div>	
	</div>
</form>