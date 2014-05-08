
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
		<h1>Password Recovery</h1>
		
		<p>Please check your email for your new password.
	</p>
	
	
	
	<?php 
		if (isset($errorMsg)) {
			echo "<p>" . $errorMsg . "</p>";
		}
	
		echo "<p>" . anchor('account/index','Login') . "</p>";
	?>	
	</td></tr>
	</table>
</body>

</html>

