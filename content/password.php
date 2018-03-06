<form method="post" enctype="multipart/form-data" action="" >
	<div class='password_change flex flex_column padding shadow round_border space_between nowrap light_grey_border border'  style="margin: 15px auto;  width: 300px;">
		<div class="flex flex_column padding">
			<label>Vecchia Password</label>
			<input class='padding' type="Password" name="oldPassword" id="oldPassword" required />
		</div>	
		<div class="flex flex_column padding">
			<label>Nuova Password</label>
			<input class='padding' type="Password" name="newPassword" id="newPassword" required />
		</div>	
		<div class="flex flex_column padding">
			<label>Conferma Nuova Password</label>
			<input class='padding' type="Password" name="confirmNewPassword" id="confirmNewPassword" required />
		</div>	
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('password','change'); ?>
		</div>
	</div>	
</form>	