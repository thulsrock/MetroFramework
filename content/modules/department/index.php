<?php

	$departmentManager = new DepartmentDAO();
	$departmentList = $departmentManager->departmentList();

?>

	<div class='flex flex_row_reverse padding_bottom space_between'>
		<?php if( isset( $_GET['target'] ) ){ ?><div class="bold"><?php echo htmlentities( $_GET['target'], ENT_QUOTES ); ?></div><?php } ?>
		<?php $this->buttonNewItem(); ?>
	</div>
	<div class='flex flex_column shadow round_border space_between nowrap light_grey_border border'>
		<?php
			$rowCount = 0;
			foreach ( $departmentList as $department ) {
				$rowColor = ( $rowCount % 2 ) ? 'light_grey_background' : 'dark_grey_background';
				$rowCount++;
		?>
			<a class="padding middle_blue_background_hover transition <?php echo esc( $rowColor ); ?>" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=target&department=" . $department->ID ); ?>" title="Visualizza obiettivi associati">
				<div class="padding">
					<?php echo htmlentities( $department->name, ENT_QUOTES ); ?>
				</div>
			</a>
		<?php } ?>
	</div>