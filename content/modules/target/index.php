<?php
    $targetManager = new Target();

	if( isset( $_GET['department'] ) ) {
		$departmentManager = new DepartmentDAO();
		$depName = $departmentManager->getNameFromID( $_GET['department'] );
		$deps[] = $_GET['department'];
    } elseif ( $this->session->hasPrivilege( 'target-monitor' ) ) {
    	$deps = NULL;
    } else {
    	try {
			$deps = [];
			$activeUserJobs = $this->session->getActiveUserJobs();
			foreach( $activeUserJobs as $job ) {
				if( isset( $job->features ) ) {
	    			foreach ( $job->features as $feature ) {
	    				if( $feature == 'target-view' ) $deps[] = $job->department;
	    			}
	    		}
	    	}
    	} catch (Exception $e) {
    		echo 'Non si dispone di privilegi di visualizzazione degli obiettivi del proprio Servizio';
    	}
   	}
    $targetList = $targetManager->list( $deps );

?>

	<div class='flex flex_row padding_bottom space_between'>
		<div class="bigger_font"><?php if( isset( $depName ) ) { ?><?php echo htmlentities( $depName, ENT_QUOTES ); ?><?php } ?></div>
		<?php $this->buttonNewItem(); ?>
	</div>
	
	<div class='flex flex_column shadow round_border space_between nowrap border light_grey_border'>
		<?php
		if( empty ( $targetList ) ) { ?>
			<div class="padding">Non sono presenti obiettivi.</div>
		<?php
			return;
		} else {
			$rowCount = 0;
			foreach ( $targetList as $target ) {
				$rowColor = ( $rowCount % 2 ) ? 'light_grey_background' : 'dark_grey_background';
				$rowCount++;
				?>
				<div class="flex flex_row <?php echo esc( $rowColor ); ?> ">	
					<div class="flex flex_column">
						<a class="light_blue_background_hover transition" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=task&department=". esc( $target->IDDepartment ) ."&target=". $target->code ); ?>" title="Visualizza attività associate" >
							<div class="flex flex_row wrap padding">
	    						<?php if( !isset( $depName ) ) { ?>
	    						<div class='flex flex_row full_grow padding bigger_font'>
									<?php echo esc( $target->department ); ?>
	    						</div>
	        					<?php } ?>
	        					<div class='flex flex_row space_between full_width'>
		    						<div class='flex flex_row padding fair_grow'>
		    							<label class='big_right_margin'>Codice</label>
		    							<div class='align_center'><?php echo htmlentities( $target->code, ENT_QUOTES ); ?></div>
		    						</div>
									<div class='flex flex_row padding fair_grow'>
										<label class='big_right_margin'>Peso</label>
										<div><?php echo htmlentities( $target->weight, ENT_QUOTES );?>%</div>
									</div>
		    						<div class='flex flex_row padding fair_grow'>
		    							<label class='big_right_margin'>Inizio</label>
		    							<div><?php echo htmlentities( $target->startDate, ENT_QUOTES ); ?></div>
		    						</div>				
		    						<div class='flex flex_row padding fair_grow'>
		    							<label class='big_right_margin'>Scadenza</label>
		    							<div><?php echo htmlentities( $target->endDate, ENT_QUOTES );?></div>
		    						</div>
		    					</div>
	    						<div class='flex flex_row full_grow padding'>
	    							<label class='big_right_margin'>Titolo</label>
	    							<div><?php echo htmlentities( $target->name, ENT_QUOTES ); ?></div>
	    						</div>
	    						<div class='flex flex_column padding'>
	    							<label style="padding-bottom: 10px;">Descrizione</label>
	    							<div class="justify"><?php echo htmlentities( $target->description, ENT_QUOTES ); ?></div>
	    						</div>		
							</div>	
						</a>	
					</div>
					
					<?php if( $this->session->hasPrivilege(TARGET_EDIT) ) { ?>
					<div class="flex flex_column light_blue_background_hover">
						<a style="height: 100%; width: 60px;" class="dark_blue_text_hover zoom" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=target&action=open&target=". $target->code ); ?>" title="Visualizza scheda obiettivo">
							<div class="flex align_middle full_height align_center" >
								<i class="material-icons transition">zoom_in</i>
							</div>
						</a>
					<!-- 	<a style="height: 50%; width: 60px;" class="zoom red_text_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=target&action=deleteTarget&target=". $target->code ); ?>" title="Elimina">
							<div class="flex align_middle full_height align_center">
								<i class="material-icons">delete</i>
							</div>
						 -->
						</a>
					</div>
					<?php } ?>
				</div>	
			<?php } ?>
		<?php } ?>
	</div>