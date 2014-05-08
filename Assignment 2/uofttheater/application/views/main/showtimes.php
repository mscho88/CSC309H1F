<?php

//And if the $site variable is not empty we echo it's content by using the generate method of the table class / library
if(!empty($showtimes))
	echo $this->table->generate($showtimes);

if(!empty($mvlist))
	echo $this->table->generate($mvlist);

echo anchor('','Back') . "<br />";

?>