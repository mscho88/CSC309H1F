<!DOCTYPE html>
<html>
<?php
	//Summary page that the user can print the receipt of the movie/venue ticket.
	echo "<table border='1'><tr><td>";
	echo "<h2 align='center'>Receipt</h2>";
	echo "UofT Theater<br><br>";
	echo "Movie : ".$summary[0]."<br>";
	echo "Theater : ".$summary[1]."<br>";
	echo "Date & Time : ".$summary[2]." ".$summary[3]."<br>";
	echo "Seat : ".$summary[4]."<br>";
	echo "Paid By Credit Card<br><br>";
	echo "Customer Info<br>";
	echo "Name : ".$summary[5]." ".$summary[6]."<br>";
	echo "CreditCard (MM/YY) : ".$summary[7]." ".$summary[8]."<br>";
	echo "</td></tr></table>";
?>
<br>For printing, <a href=# onclick="window.print();return false;">click here</a> or press Ctrl+P
</html>