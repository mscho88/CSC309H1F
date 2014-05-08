
<!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/mainPages.css"/>
	</head> 
<body>  
	<table background=<?php echo base_url().'images/bg.png'; ?>>
	<tr><td>
		<input type="image" src="<?php echo base_url().'images/top.png'; ?>" width="800"></input>
	</td></tr>
	<tr><td>
	<h1 align="center">Recover Password</h1>
<?php 
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}

	echo form_open('account/recoverPassword');
	echo form_label('Email'); 
	echo form_error('email');
	echo form_input('email',set_value('email'),"required");
	echo form_submit('submit', 'Recover Password');
	echo form_close();
	
	echo form_open('account/loginForm');
	echo form_submit('submit', 'Go back to main');
	echo form_close();
?>	
</td></tr>
	</table>
</body>

</html>

