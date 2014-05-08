<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet" type="text/css" />
	<noscript>
		Javascript is not enabled! Please turn on Javascript to use this site.
	</noscript>
	
	<script type="text/javascript">
	//<![CDATA[
		base_url = '<?= base_url();?>';
	//]]>
	</script>
	
	
	
	<script type="text/javascript" src="<?php echo base_url();?>js/prototype.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/scriptaculous.js" ></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/customtools.js" ></script>
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<style type="text/css">
		.ui-datepicker {
			background-color: #e0e0e0;  
			width: 200px;  
			margin: 5px auto 0;  
			box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
		}
		.ui-datepicker table {  
	    	width: 100%;
		}
		.ui-datepicker-title {  
			text-align: center;
		} 
		.ui-datepicker thead {  
			background-color: #f7f7f7;
			text-transform: uppercase;
			font-size: 8pt;
			padding: 5px 0;
			color: #666666;
			text-shadow: 1px 0px 0px #fff;
	    }
	    .ui-datepicker-prev {
	    	float: left;
	    }
	    .ui-datepicker-next {
	    	float: right;
	    }
	</style>
	<script type="text/javascript">
		var date = new Date();
		var year = date.getFullYear(), month = date.getMonth(), day = date.getDate();
		$(document).ready(function() {
			$("#calendar").datepicker({
				minDate: new Date(year, month, day+1),
				maxDate: new Date(year, month, day+14),
				dateFormat: 'yy-mm-dd',
				numberOfMonths: 1
			});
		});
	</script> 
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<?php $this->load->view('header');?>
		</div>
		
		<div style="float:left; width:200px;">
			<?php
				$att = array(
						'id'=>'calendar',
						'name'=>'date',
						'value'=>$date,
						'style'=>'width:98px'
						);
				echo form_open('main/showMovieOrVenue');
				echo form_error('date');
				echo form_input($att, set_value('date'), "required pattern='\d{4}-\d{2}-\d{2}'");

				$bool = array(true, true);
				if($mv == "movie"){
					$bool[1] = false;
				}else if($mv == "venue"){
					$bool[0] = false;
				}
				echo form_radio('mv', 'movie', $bool[0]);
				echo form_label('Movie');
				
				echo form_radio('mv', 'venue', $bool[1]);
				echo form_label('Venue');
				
				echo form_submit('add', 'Show List');
				echo form_close();
			?>
		</div>
		<div id="main" style="float:left">
			<?php $this->load-> view($main);?>
		</div>

		<div id="footer"> 
			<?php $this->load->view('footer');?>
		</div>
	</div>
</body>
</html>
