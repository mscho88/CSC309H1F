<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

	$config = array(
		'main/register' => array(
			array(
					'field' => 'firstname' ,
					'label' => 'First Name : ' ,
					'rules' => 'required'
			),
			array(
					'field' => 'lastname' ,
					'label' => 'Last Name : ' ,
					'rules' => 'required'
			),
			array(
					'field' => 'cnn' ,
					'label' => 'Credit Card Number (16 digits) : ' ,
					'rules' => 'required|callback_ccn_check'
			),
			array( 'field' => 'ed' ,
					'label' => 'Expiration Date : ' ,
					'rules' => 'required|callback_ed_check'
			)
		)
	);
?>