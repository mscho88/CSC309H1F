
<!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/mainPages.css"/>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script>
			function checkPassword() {
				var p1 = $("#pass1"); 
				var p2 = $("#pass2");
				
				if (p1.val() == p2.val()) {
					p1.get(0).setCustomValidity("");  // All is well, clear error message
					return true;
				}	
				else	 {
					p1.get(0).setCustomValidity("Passwords do not match");
					return false;
				}
			}
		</script>
	</head> 
<body>  
	<table background=<?php echo base_url().'images/bg.png'; ?>>
	<tr><td>
		<input type="image" src="<?php echo base_url().'images/top.png'; ?>" width="800"></input>
	</td></tr>
	<tr><td>
	<h1 align="center">Change Password</h1>
<?php 
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}

	echo form_open('account/updatePassword');
	echo form_label('Current Password'); 
	echo form_error('oldPassword');
	echo form_password('oldPassword',set_value('oldPassword'),"required");
	echo form_label('New Password'); 
	echo form_error('newPassword');
	echo form_password('newPassword','',"id='pass1' required");
	echo form_label('Password Confirmation'); 
	echo form_error('passconf');
	echo form_password('passconf','',"id='pass2' required oninput='checkPassword();'");
	echo form_submit('submit', 'Change Password');
	echo form_close();
?>	
	</td></tr>
	</table>
</body>

</html>

