
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
		<input type="image" src="<?php echo base_url().'images/top.png'; ?>" width="800"/>
	</td></tr>
	<tr><td>
		<h1 align="center">New Account</h1>
		<?php 
		echo form_open('account/createNew');
		echo form_label('Username'); 
		echo form_error('username');
		echo form_input('username',set_value('username'),"required");
		echo form_label('Password'); 
		echo form_error('password');
		echo form_password('password','',"id='pass1' required");
		echo form_label('Password Confirmation'); 
		echo form_error('passconf');
		echo form_password('passconf','',"id='pass2' required oninput='checkPassword();'");
		echo form_label('First');
		echo form_error('first');
		echo form_input('first',set_value('first'),"required");
		echo form_label('Last');
		echo form_error('last');
		echo form_input('last',set_value('last'),"required");
		echo form_label('Email');
		echo form_error('email');
		echo form_input('email',set_value('email'),"required");
		?>
		
		<!-- This generate secure image (captcha) by using securimage plugin -->
		<img id="captcha" src="<?= base_url()?>securimage/securimage_show.php" alt="CAPTCHA Image" align="middle" />
		
		<?php
		//Make input form for captcha and set minimum condition
		echo form_label('Verification Code');
		echo form_error('captcha_code');
		echo form_input('captcha_code','',"id='captcha_code' required pattern=\w{6} title='enter what you see above' ");
		?>
		
		<!-- This button refresh the secure image (captcha) -->
		<input type="button" onclick="document.getElementById('captcha').src = '<?= base_url()?>securimage/securimage_show.php?' + Math.random(); return false" value="Different Image"/>
		<?php 
		echo form_submit('submit', 'Register');
		echo form_close();
		
		echo form_open('account/loginForm');
		echo form_submit('submit', 'Go back to main');
		echo form_close();
		?>
	</td></tr>
	</table>
</body>

</html>

