<?php

	$department = new DepartmentDAO();
	$departmentList = $department->departmentList();

	$feature = new FeatureDao();
	$featureList = $feature->getFeatureFullList();

?>
<form method="post" enctype="multipart/form-data" action="" >
	<div class='flex flex_column shadow round_border space_between nowrap border padding'>
		<div class="flex flex_row full_grow">
			<div class='flex flex_column padding fair_grow space_between'>
				<label class='middle_blue_text bold'>Nome</label>
				<input class='padding' type="text" name="name" id="name" required />
			</div>
			<div class='flex flex_column padding fair_grow'>
				<label class='middle_blue_text bold'>Cognome</label>
				<input class='padding' type="text" name="surname" id="surname" required />
			</div>
			<div class='flex flex_column padding fair_grow'>
				<label class='middle_blue_text bold'>Username</label>
				<input class='padding' type="text" name="username" id="username" />
			</div>
			<div class='flex flex_column padding fair_grow'>
				<label class='middle_blue_text bold'>Password</label>
				<input class='padding' type="password" name="password" id="password" value="<?php echo esc( PASS_DEFAULT ); ?>" class="soft_border" required />
			</div>
		</div>
		<div class='flex flex_row wrap full_grow space_between'>
			<div class='flex flex_column padding '>
				<label class='middle_blue_text bold'>Matricola</label>
				<input class='padding' type="number" name="serialNumber" id="serialnumber" />
			</div>
			<div class='flex flex_column padding '>
				<label class='middle_blue_text bold'>Codice Fiscale</label>
				<input class='padding' type="text" name="SSN" id="ssn" size=16 />
			</div>
			<div class='flex flex_column padding five_grow'>
				<label class='middle_blue_text bold'>Email</label>
				<input class='padding' type="email" name="email" id="email" />
			</div>
			<input class='full_height' type="hidden" name="disabled" id="disabled" value="0"  />
		</div>
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('user', 'new'); ?> 
		</div>
	</div>
</form>