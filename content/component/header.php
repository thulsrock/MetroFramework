<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta charset="UTF-8">  
		<link rel='stylesheet' type='text/css' href='<?php echo $this->getStyle(); ?>' />
		<link rel='stylesheet' type='text/css' href='<?php echo $this->getMaterialIconStyle(); ?>' />
		<link href="https://fonts.googleapis.com/css?family=Poppins:300" rel="stylesheet"> 
	 	<script src='<?php echo JQUERY; ?>'></script>
	 	<script src='<?php echo JQUERYUI; ?>'></script>
			
		<title><?php echo SITENAME . ' - ' . COMPANY_NAME; ?></title>
	</head>
	<body  class='flex flex_column'>
		<header class="flex flex_column align_center" style="padding: 15px 0;">
			<div id='logo' class='flex flex_row align_center'>
				<a href="<?php echo htmlspecialchars( ROOT ); ?>" title="Pagina dei Servizi">
					<img src='<?php echo htmlspecialchars( HEADER_LOGO ); ?>' />
				</a>
				<a class="dark_green_text_hover transition" href="<?php echo htmlspecialchars( ROOT ); ?>" title="Pagina dei Servizi">
					<div class='flex flex_column align_center'>
						<div class='biggest_font'><?php echo esc( COMPANY_NAME ); ?></div>
						<div class='bigger_font small_caps'><?php echo htmlspecialchars( SITENAME, ENT_QUOTES ); ?></div>
					</div>
				</a>
			</div>
		</header>
		<?php $this->navigationMenu(); ?>
		<main>