
<!DOCTYPE html>

<html>
	<head>
	<title> Game Area </title>
	<style type="text/css">
		h1{
			position: absolute;
			top:180px;
			left:380px
		}
		body{
			font-family:"Courier New", Courier, monospace;
			font-size:20px;
		}
		div#new_disc{
			display: none;
			border-radius: 50%;
            width: 44px;
            height: 44px;
		}
		div[id*="disc"] {
			display: none;
			border-radius: 50%;
            position: absolute;
            width: 44px;
            height: 44px;
		}
		input[type=button]{
			background: url(<?= base_url()?>images/arrow.png) no-repeat;
			border: none;
			cursor: pointer;
			float: left;
			margin-top: 20px;
			width: 60px;
			height: 60px;
		}
		.arrow{
			position: absolute;
			left:30px;
			top:256px;
		}
		#a2{
			left:90px;
		}
		#a3{
			left:150px;
		}
		#a4{
			left:210px;
		}
		#a5{
			left:270px;
		}
		#a6{
			left:330px;
		}
		#a7{
			left:390px;
		}
		#board{
			position: absolute;
			clear:left;
			float:left;
			top:335px;
			left:10px;
			margin-top:10px;
			margin-left:20px;
			margin-bottom:30px;
		}
		[name=msg]{
			width:200px;
		}
		[name=conversation]{
			resize: none;
		}
		input[type="submit"]{
			width: 98px;
			height: 25px;
			border-top-left-radius: 38px;
			border-bottom-right-radius: 38px;
			font-family:"Courier New", Courier, monospace;
		}
    </style>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script>

		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		
		$(function(){
			$('body').everyTime(500,function(){
				if (status == 'waiting') {
					$.getJSON('<?= base_url() ?>arcade/checkInvitation',function(data, text, jqZHR){
						if (data && data.status=='rejected') {
							alert("Sorry, your invitation to play was declined!");
							window.location.href = '<?= base_url() ?>arcade/index';
						}
						if (data && data.status=='accepted') {
							status = 'playing';
							$('#status').html('Playing ' + otherUser);
						}
					});
				}
				var url = "<?= base_url() ?>board/getMsg";
				$.getJSON(url, function (data,text,jqXHR){
					if (data && data.status=='success') {
						var conversation = $('[name=conversation]').val();
						var msg = data.message;
						if (msg.length > 0)
							$('[name=conversation]').val(conversation + "\n" + otherUser + ": " + msg);
					}
				});

				$.getJSON("<?= base_url()?>board/getDisc", function(data, text, jqXHR){
					if (text && text == 'success'){
						var discLeft = $("#"+"a"+data[2]).offset().left;
						var discTop = $("#"+"a"+data[2]).offset().top;
						var boardLeft = $("#board").offset().left;
			            var boardTop = $("#board").offset().top;

			            var dist = 653 - (data[1] -1) * 60; 

			            if(<?= $isHost?> == <?= $otherUser->id?>){
							$("#new_disc").css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 5%, gray 30%, white 98%)");
						}else{
							$("#new_disc").css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 20%, gray 80%, white 90%)");
						}
						
			            $("#new_disc").offset({left:(discLeft + 7), top:(discTop + 50)});
			            
			            $("#new_disc").css("display", "block").animate({top:dist}, 400, function(){
			            	$("#new_disc").animate({top:dist-dist/5}, 300, function(){
			            		$("#new_disc").animate({top:dist}, 250, function(){
			            			$("#new_disc").animate({top:dist-dist/8}, 200, function(){
			            				$("#new_disc").animate({top:dist}, 200, function(){
				            				$("#new_disc").offset({left:0, top:0});
				            				$("#new_disc").css("display", "none");
				            				if(<?= $isHost?> == <?= $otherUser->id?>){
				    							$("#"+"disc"+data[1]+data[2]).css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 5%, gray 30%, white 98%)");
				    						}else{
				    							$("#"+"disc"+data[1]+data[2]).css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 20%, gray 80%, white 90%)");
				    						}
			            					$("#"+"disc"+data[1]+data[2]).css("display", "block");
			            					$("#status_message").text("It's your turn to choose.");
			            					$(".arrow").removeAttr("disabled");

			            					//wincase
			            					$.getJSON("<?=base_url()?>board/isWin", function(data, text, jqXHR){
				            					//pop up window
			            					});
						            	});
					            	});
				            	});
			            	});
						});
					}
				});
			});
			
			
			$(".arrow").click(function(){
				// When one of the buttons is clicked, disable them so that it won't make any error during it animates.
				
				var arg = $(this).attr("id");

				var discLeft = $(this).offset().left;
	            var discTop = $(this).offset().top;

	            var boardLeft = $("#board").offset().left;
	            var boardTop = $("#board").offset().top;

	            var dist = 653;
	            var aDiv;

	            $.post("<?= base_url()?>board/postDisc", {selectedColumn:arg.slice(1,2)-1}, function(data, textStatus, jqXHR){
		            alert(data);
	            	if(textStatus == "success" && data != "error"){
	            		$(".arrow").attr("disabled", "disabled");
	            		dist -= (data - 1) * 60;
		            	$("#new_disc").offset({left:(discLeft + 7), top:(discTop + 50)});

						if(<?= $isHost?> == <?= $user->id?>){
							$("#new_disc").css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 5%, gray 30%, white 98%)");
						}else{
							$("#new_disc").css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 20%, gray 80%, white 90%)");
						}
		            	
			            $("#new_disc").css("display", "block").animate({top:dist}, 400, function(){
			            	$("#new_disc").animate({top:dist-dist/5}, 300, function(){
			            		$("#new_disc").animate({top:dist}, 250, function(){
			            			$("#new_disc").animate({top:dist-dist/8}, 200, function(){
			            				$("#new_disc").animate({top:dist}, 200, function(){
			            					$("#new_disc").css("display", "none");

			            					if(<?= $isHost?> == <?= $user->id?>){
			        							$("#"+"disc"+data+""+arg.slice(1,2)).css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 5%, gray 30%, white 98%)");
			        						}else{
			        							$("#"+"disc"+data+""+arg.slice(1,2)).css("background-image", "-moz-radial-gradient(12px 12px 225deg, circle cover, black 20%, gray 80%, white 90%)");
			        						}
			            					$("#"+"disc"+data+""+arg.slice(1,2)).css("display", "block");
			            					
			            					$("#status_message").text("Nice !");

			            					//wincase
			            					$.post("<?=base_url()?>board/isWin", {row:data, column:arg.slice(1,2)}, function(data, text, jqXHR){
				            					//pop up window
			            					});
						            	});
					            	});
				            	});
			            	});
						});
					}else{
						$("#status_message").text("The column is full!");
	            	}
	            });
			});
			
			$('form').submit(function(){
				var arguments = $(this).serialize();
				var url = "<?= base_url() ?>board/postMsg";
				$.post(url, arguments, function (data, textStatus, jqXHR){
					var conversation = $('[name=conversation]').val();
					var msg = $('[name=msg]').val();
					$('[name=conversation]').val(conversation + "\n" + user + ": " + msg);
					$('[name=msg]').val('');
				});
				return false;
			});	
			
			$(document).ready(function(){
				// Put disc divs in each whole on the board.
				var boardLeft = $("#board").offset().left+8;
	            var boardTop = $("#board").offset().top+8;
				for(var i = 6; i >= 1; i--){
					for(var j = 1; j <= 7; j++){
						$("#" + "disc" + i + "" + j).offset({left : boardLeft + (j - 1) * 60, top : boardTop + (6 - i) * 60});
					}
				}
			});
		});
	</script>
	</head> 
	<body>  
		<table background=<?= base_url().'images/bg.png'; ?>>
			<tr><td colspan="2">
			<input type="image" src="<?= base_url().'images/top.png'; ?>" width="800"/>
			<h1>Game Area</h1>
			</td></tr>
			
			<tr><td height="480" width="460" valign="top">
				<?php
				for($i = 1; $i <= 7; $i++){
					if($user->id == $isHost){
						echo "<input type='button' name='a$i' id='a$i' class='arrow' src='".base_url()."images/arrow.png'/>";
					}else{
						echo "<input type='button' name='a$i' id='a$i' class='arrow' src='".base_url()."images/arrow.png' disabled/>";
					}
				}
				echo "<div id='new_disc'></div>";
				?>
				<input type="image" id="board" src="<?= base_url().'images/connect4.png';?>"/>
				
				<?php 
				// Define 6*7 divs
				for($j = 6; $j >= 1; $j--){
					for($k = 1; $k <= 7; $k++){
						echo "<div id='disc$j$k'></div>";
					}
				}
				?>
			</td>
			<td width="360" valign="top">
				<div>
				<br>
				Welcome to CONNECT4,<br>&nbsp;&nbsp; <?= $user->fullName() ?>
				<?= anchor('account/logout','(Logout)') ?>  
				</div>
				
				<div id='status'> 
					<?php 
						if ($status == "playing")
							echo "Playing against " . $otherUser->login;
						else
							echo "Wating on " . $otherUser->login;
					?>
				</div>
				<br>
				<?php
					echo form_textarea('conversation');
					echo form_open();
					echo form_input('msg');
					echo form_submit('Send','Send');
					echo form_close();
				?>
				<br>
				Status : <div id="status_message">
					<?php 
					if($user->id == $isHost){
						echo "You are a host. You begin first.";
					}else{
						echo "You are invited. Waiting for the host's choice.";
					}
					?>
					</div>
			</td></tr>
		</table>
	</body>
</html>

