<?php

	$userManager = new User();
	
	$userID = $_GET['user'];
	$user = $userManager->getUserDetailFromID( $userID );
	$jobs = $userManager->getJobsAndFeaturesFromUserID( $userID );

	$departmentManager = new DepartmentDAO();
	$departmentList = $departmentManager->departmentList();

?>
<form method="post" enctype="multipart/form-data" action="" accept-charset="UTF-8" >
	<div class="user_edit flex flex_column shadow round_border space_around nowrap light_grey_border border padding" style='margin:20px auto;'>
		<input type="hidden" name="ID" value="<?php echo esc( $user->ID ); ?>" />
		<div class="flex flex_row wrap">
			<div class="flex flex_column padding fair_grow">
				<label>Cognome</label>
				<input class="padding" type="text" name="surname" value="<?php echo esc( $user->surname ); ?>" />
			</div>
			<div class="flex flex_column padding fair_grow">
				<label>Nome</label>
				<input class="padding" type="text" name="name" value="<?php echo esc( $user->name ); ?>" />
			</div>
			<div class="flex flex_column padding fair_grow">
				<label>Username</label>
				<input class="padding" type="text" name="username" value="<?php echo esc( $user->username ); ?>" />
			</div>
			<div class="flex flex_column padding">
				<label>Matricola</label>
				<input class="padding" type="text" name="serialNumber" value="<?php echo esc( $user->serialNumber ); ?>" />
			</div>
			<div class="flex flex_column padding">
				<label>CF</label>
				<input class="padding" type="text" name="SSN" value="<?php echo esc( $user->SSN ); ?>" />
			</div>
			<div class="flex flex_column padding fair_grow">
				<label>Email</label>
				<input class="padding" type="text" name="email" value="<?php echo esc( $user->email ); ?>" />
			</div>
			<div class="flex flex_column padding space_between">
				<label>Bloccato</label>
				<div class="flex flex_row">
					<?php $disableChecked = ( $user->disabled == 1 ) ? " checked" : NULL; ?>
					<div>Si<input class="padding"  type="radio" name="disabled" value="1" <?php echo $disableChecked; ?> /></div>
					<?php $disableChecked = ( $user->disabled == 0 ) ? " checked" : NULL; ?>
					<div>No<input class="padding"  type="radio" name="disabled" value="0" <?php echo $disableChecked; ?> /></div>
				</div>
			</div>
		</div>
		<div class="light_grey_background round_border">
			<div class="padding uppercase bold">Storico posizioni lavorative</div>
			<div class="flex flex_column full_width">	
					
				<div class="flex flex_row full_width">
					<div class="padding align_center" style="width: 15%"><label>Data di inizio</label></div>
					<div class="padding align_center" style="width: 15%"><label>Data di fine</label></div>
					<div class="padding align_center" style="width: 60%"><label>Servizio</label></div>
					<div style="text-align: center; width: 10%"></div>
				</div>
				
				<?php
					foreach ( $jobs as $jobID => $job ) {
						$departmentName = $departmentManager->getNameFromID( $job->department );
				?>			
				
				<div class="flex flex_row full_width light_blue_background_hover">
					<a class='flex flex_row full_width middle_blue_border' href='<?php echo esc( $_SERVER['PHP_SELF'] ); ?>?module=user&user=<?php echo $userID; ?>&action=edit-feature&userjob=<?php echo $jobID;?>' title="Dettaglio posizione lavorativa" >
						<div class="padding align_center" style="width: 15%"><?php echo htmlentities( $job->startDate ); ?></div>
						<div class="padding align_center" style="width: 15%"><?php echo htmlentities( $job->endDate ); ?></div>
						<div class="padding" style="width: 60%"><?php echo htmlentities( $departmentName ); ?></div>
					</a>
					<div>
						<a class='red_text_hover zoom block padding' href="<?php echo htmlentities( $_SERVER['PHP_SELF'] .'?module=user&user='.$user->ID.'&action=deleteUserJob&jobID='  ) . $jobID; ?>" title="Elimina posizione lavorativa"><i class="material-icons align_middle">&#xE14C;</i></a>
					</div>
				</div>
				<?php } // end foreach ?>	
			
				<div class="flex flex_row full_width">
					<div class="padding align_center" style="width: 15%"><input class="padding" type="date" name="startDate" /></div>
					<div class="padding align_center" style="width: 15%"><input class="padding" type="date" name="endDate" /></div>
					<div class="padding align_center" style="width: 60%">
						<select class="full_width single_line" name="department" >
							<option class="padding full_width" value="">Selezionare un Servizio</option>
							<?php foreach( $departmentList as $department ) { ?>
							<option class="padding full_width" value="<?php echo htmlentities( $department->ID ); ?>"><?php echo htmlentities( $department->name ); ?></option>
							<?php }?>
						</select>
					</div>
					<div style="text-align: center; width: 10%"></div>
				</div>
			</div>
		</div>
		<div class='flex wrap align_center padding'>
			<?php $this->buttonFormSend('user', 'edit'); ?>
		</div>
	</div>
</form>