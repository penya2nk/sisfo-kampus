<?php
	include 'constant.php';
	include 'model/pmbperiod.php';
	
	class MyModel {
		function __construct() {
			$this->pmbperiod_model = new pmbperiod_model();
			
			return $this;
		}
	}
	
	$ci = new MyModel();