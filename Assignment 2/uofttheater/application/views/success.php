<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			input[type=button]{
				margin: 10px;
				border: solid 3px;
				border-color: #ABABAB
			}
		</style>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript">
			//Client side input validation
			function checkEd(){
				var expirationDate = $('#ed');
				var time = new Date();
				var month = time.getMonth() + 1;
				var year = time.getFullYear() % 100;
				//var year = time.getFullYear().toString().slice(2,4);
				
				var expirationMM = expirationDate.val() / 100;
				var expirationYY = expirationDate.val() % 100;
				var bool;
				
				if(year < expirationYY && expirationMM < 13)		{bool = true;}
				else if(year > expirationYY)						{bool = false;}
				else{
					if(month <= expirationMM && expirationMM < 13)	{bool = true;}
					else											{bool = false;}
				}

				if(bool == true){
					expirationDate.get(0).setCustomValidity("");
				}else if(bool == false){
					expirationDate.get(0).setCustomValidity("Invalid Credit Card Expiration Date.");
				}
				return bool;
			}
			
			var selected = null;
			function seatSelect(btnId, selectedSeat){
				btn = document.getElementById(btnId);
				if(selected == null)
					selected = document.getElementById(selectedSeat);
				selected.style.background = 'white';
				selected = btn;
				selected.style.background = '#00FF00';

				document.getElementById('seat').value = btnId;
				return selected == null ? false : true;
			}
		</script>
	</head>
	<body>
	<?php

		echo "<table align='center'>";
		echo "<tr><td colspan='2' align='center'>";
		echo "<h1>Customer Information</h1>";
		echo "</td></tr>";
		echo "<tr><td colspan='2' align='center'>";
		
		echo "<table border='1'>";
		echo "<tr><td align='center'>";
		echo "SCREEN<br>";
		echo "</tr></td>";
		echo "<tr><td align='center'>";
				
		$i = 1;
		$selectedSeat = null;
		foreach($seats as $seat){
			if($seat == true){
				if($selectedSeat == null){
					$selectedSeat = $i;
					$arr = array(
						'id' => $i,
						'style' => 'background-color:#00FF00',
						'onclick' => 'seatSelect('.$i.', '.$selectedSeat.')'
					);
					//echo form_button($arr);
					echo "<input type='button' id='".$i."' style='background-color:#00FF00' onclick='seatSelect(".$i.", $selectedSeat)'/>";
				}else{
					echo "<input type='button' id='".$i."' style='background-color:white' onclick='seatSelect(".$i.", $selectedSeat)'/>";
				}
			}else{
				echo "<input type='button' diabled id='".$i."' style='background-color:yellow'/>";
			}
			$i++;
		}
		echo "</td></tr>";
		echo "<tr><td align='center'>";
		echo "<input type='button' style='background-color:yellow'/>: Booked";
		echo "<input type='button' style='background-color:#00FF00'/>: Selected";
		echo "<input type='button' style='background-color:white'/>: Available&nbsp;";
		echo "</td></tr>";
		echo "</table>";
		echo "</td></tr>";
		
		echo validation_errors();
		echo form_open('main/register/'.$showtimeid);
				
		echo "<tr><td>";
		echo form_error('seat');
		echo form_input(array('name' => 'seat', 'id' => 'seat', 'type' => 'hidden'), $selectedSeat, '');
		echo "</td></tr>";
		
		echo "<tr><td>";
		echo form_label('First Name : ');
		echo "</td><td>";
		echo form_error('firstname');
		echo form_input('firstname',set_value('firstname'),"required pattern=\w{1,45} title='First name should be the length of 1 to 45.'");
		echo "<br>";
		echo "</td></tr>";
		
		echo "<tr><td>";
		echo form_label('Last Name : ');
		echo "</td><td>";
		echo form_error('lastname');
		echo form_input('lastname',set_value('lastname'),"required pattern=\w{1,45} title='Last name should be the length of 1 to 45.'");
		echo "<br>";
		echo "</td></tr>";
		
		echo "<tr><td>";
		echo form_label('Credit Card Number (16 digits) : ');
		echo "</td><td>";
		echo form_error('ccn');
		echo form_input('ccn','',"required pattern='\d{16}' title='XXXXXXXXXXXXXXXX'");
		echo "<br>";
		echo "</td></tr>";
		
		echo "<tr><td>";
		echo form_label('Expiration Date : ');
		echo "</td><td>";
		echo form_error('ed');
		echo form_input('ed','',"id='ed' required pattern='\d{4}' title='MMYY' oninput='checkEd()'");
		echo "</td></tr>";
		
		echo "<tr><td colspan='2' align='center'>";
		echo form_submit('submit','Confirm');
		echo "</td></tr>";
		
		
		echo form_close();
		echo "</table>";
	?>
	</body>
</html>