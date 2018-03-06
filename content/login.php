<div class="login flex flex_column">
	<div class="flex flex_column padding box_padding" style="margin: 0px auto; margin-top: 30px;">
		<form method="post" enctype="multipart/form-data" action="" >
			<div class="padding">
				<input type="text" placeholder="Username" class="round_border transition" name="username" id="username" size=30 />
			</div>
			<div class="padding">
				<input type="password" placeholder="Password" class="round_border transition" name="password" id="password" size=30 />
			</div>
		
			<div class="flex flex_row space_between padding align_middle">
				<?php $this->buttonFormSend('login'); ?>
		<!-- 	<a class="forgotten_password padding inline_block underline_hover" href="">Password dimenticata</a>		-->
			</div>
		 
		</form>
	</div>
	<div class="flex flex_column" style="position: absolute; top: 220px; right: 60px;">
		<table cellpadding=5>
			<tr><th>Username</th><th>Password</th><th>Ruolo</th></tr>
			<tr><td>f.peragine</td><td>Provincia1</td><td>Amministratore</td></tr>
			<tr><td>f.capozzi</td><td>Provincia1</td><td>Servizio</td></tr>
			<tr><td>d.mundo</td><td>Provincia1</td><td>Nucleo</td></tr>
		</table>
	</div>
	
	<div class="flex flex_column space_around padding" style="position: static; bottom: 20px;">
		<div class="align_center">Compatibilità dei browser</div>
		<div class="flex flex_row align_center">
			<div class="flex flex_column padding">
				<div class="compatible_chrome" title="Chrome"></div>
				<label>29.0</label>
			</div>
			<div class="flex flex_column padding">
				<div class="compatible_edge" title="Edge"></div>
				<label>11.0</label>
			</div>
			<div class="flex flex_column padding">
				<div class="compatible_firefox" title="Firefox"></div>
				<label>22.0</label>
			</div>
			<div class="flex flex_column padding">
				<div class="compatible_safari" title="Safari"></div>
				<label>10.0</label>
			</div>
			<div class="flex flex_column padding">
				<div class="compatible_opera" title="Opera"></div>
				<label>48.0</label>
			</div>
		</div>
	</div>
</div>