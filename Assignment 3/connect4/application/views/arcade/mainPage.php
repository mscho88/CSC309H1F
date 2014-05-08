
<!DOCTYPE html>

<html>
	
	<head>
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/mainPages.css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script>
		$(function(){
			$('#availableUsers').everyTime(500,function(){
					$('#availableUsers').load('<?= base_url() ?>arcade/getAvailableUsers');

					$.getJSON('<?= base_url() ?>arcade/getInvitation',function(data, text, jqZHR){
							if (data && data.invited) {
								var user=data.login;
								var time=data.time;
								if(confirm('Play ' + user)) 
									$.getJSON('<?= base_url() ?>arcade/acceptInvitation',function(data, text, jqZHR){
										if (data && data.status == 'success')
											window.location.href = '<?= base_url() ?>board/index'
									});
								else  
									$.post("<?= base_url() ?>arcade/declineInvitation");
							}
						});
				});
			});
	
	</script>
	</head> 
<body>  
	<table background=<?php echo base_url().'images/bg.png'; ?>>
	<tr><td>
		<input type="image" src="<?php echo base_url().'images/top.png'; ?>" width="800"></input>
	</td></tr>
	<tr><td>
	<div align="center">
	Welcome to CONNECT 4,  <?= $user->fullName() ?>  <?= anchor('account/logout','(Logout)') ?>  <?= anchor('account/updatePasswordForm','(Change Password)') ?>
	</div>
	
<?php 
	if (isset($errmsg)) 
		echo "<p>$errmsg</p>";
?>
	</td></tr>
	<tr><td>
	
	<h2>&nbsp;Available Users</h2>
	<div id="availableUsers">
	</div>
	</td></tr>
	</table>
	
	
</body>

</html>

