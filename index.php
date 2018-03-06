<?php 
	session_start();
	
	require_once 'config.php';
	require_once 'functions.php';
	
	error_reporting( ERROR_REPORTING );
	
	$session = new Session();
	
	$em = new EventMediator();
	$em->main( $session );