
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
	<h1 align="center">Login</h1>
	<?php 
		if (isset($errorMsg)) {
			echo "<p>" . $errorMsg . "</p>";
		}
	
		echo form_open('account/login');
		echo form_label('Username'); 
		echo form_error('username');
		echo form_input('username',set_value('username'),"required");
		echo form_label('Password'); 
		echo form_error('password');
		echo form_password('password','',"required");
		
		echo form_submit('submit', 'Login');
		
		echo "<p>" . anchor('account/newForm','Create Account') . "</p>";
	
		echo "<p>" . anchor('account/recoverPasswordForm','Recover Password') . "</p>";
		
		
		echo form_close();
	?>	
	</td></tr>
	</table>
</body>

</html>

