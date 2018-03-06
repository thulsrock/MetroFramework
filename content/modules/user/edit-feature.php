<?php
	$jobID = $_GET['userjob'];
	
	$jobManager = new JobDAO();
	$userID = $jobManager->getUserIDFromJobID( $jobID );
	
	$userManager = new User();
	$user = $userManager->getUserDetailFromID( $userID );
	$job = $userManager->getJobDetailFromID( $jobID );
	
	$featureManager = new FeatureDAO();
	$featureFullList = $featureManager->getFeatureFullList();
	$job->features = $featureManager->getFeatureFromJobID( $jobID );
	
	$departmentManager = new DepartmentDAO();
	$departmentList = $departmentManager->departmentList();
	$departmentName = $departmentManager->getNameFromID( $job->department );	
	
	$userURL =  $_SERVER['PHP_SELF'].'?module=user&action=edit&user='.esc( $userID );
?>
<div class='flex flex_column shadow round_border space_between nowrap border light_grey_border padding'>
	<div class="flex flex_column padding">
		<a class="bigger_font bold uppercase underline_hover" href="<?php echo htmlspecialchars( $userURL ); ?>" title="Torna a scheda utente">
			<?php echo esc( $user->surname . ' ' . $user->name ); ?>
		</a>
	</div>
	<form method="post" enctype="multipart/form-data" action="" >
		<div class="user_edit_feature flex flex_row">
			<div class="flex flex_column">
				<label class='padding'>Servizio</label>
				<div class='padding'><?php echo $departmentName; ?></div>
				<div class="flex flex_row">
					<label class='padding'>Data di inzio</label>
					<div class='padding'><?php echo $job->startDate; ?></div>
					<label class='padding'>Data di fine</label>
					<div class='padding'><?php echo $job->endDate; ?></div>
				</div>	
			</div>
			<div class="flex flex_column double_grow padding">
				<?php 
					try { ?>
						<select class='padding' name="userjob_feature[]" id="multiSelect" multiple="multiple">
						<?php 
						foreach ( $featureFullList as $feature ) {
							$checked = NULL;
							try {
								foreach( $job->features as $jobFeature ) {
									if( $feature->code == $jobFeature->code ) {
										$checked = 'selected';
										break;
									}
								}
							} catch (Exception $e) {
								
							} ?>
							
							<option style="padding: 5px;" value=<?php echo esc( $feature->code );?> <?php echo esc( $checked ); ?> ><?php echo htmlentities( $feature->name, ENT_QUOTES ); ?></option>
						<?php } ?>
						</select>
					<?php 	
					} catch (Exception $e) {
					}
				?>
			</div>
		</div>
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('user','edit-feature'); ?>
		</div>
	</form>
</div>