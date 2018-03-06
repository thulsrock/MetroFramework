<?php

class Session {
	
	private $ID;

	public function __construct() {
		if( PHP_SESSION_NONE ) $this->ID = session_id();
		
		if( !isset( $_SESSION['user_id'] ) ) {
			$_SESSION['user_id'] = 0;
		}	
		if( !isset( $_SESSION['user_is_logged'] ) ) {
			$_SESSION['user_is_logged'] = FALSE;
		}
	}
	
	public function setGlobals ( String $username ) {
		$_SESSION['user_is_logged'] = TRUE;
		$user = new User();
		
		try {
			$_SESSION['user_id'] = $user->getUserIDFromUsername( $username );
			$_SESSION['username'] = $username;
			$_SESSION['jobs'] = $user->getJobsAndFeaturesFromUserID( $_SESSION['user_id'] );
		} catch (Exception $e) {
			throw new Exception('Impossibile inizializzare le variabili');
		}

	}
	
	public function getActiveUserJobs() {
		return $_SESSION['jobs'];
	}

	public function isLogged() {
		return $_SESSION['user_is_logged'] == TRUE;
	}
	
	public function isNotLogged() {
		return !$this->isLogged();
	}

	public function getUsername() {
		return $_SESSION['username'];
	}
	
	public function hasPrivilege( $privilege ) {
		foreach( $this->getActiveUserJobs() as $job ) {
			if( isset( $job->features ) ) {
				foreach ( $job->features as $feature ) {
					if( strpos( $feature, $privilege ) !== FALSE ) {
						return TRUE;
					}
				}
			}
		} 
	}

	public function getDepartments() {
		foreach( $_SESSION['jobs'] as $job ) {
			$deps[] = $job['department'];
		}
		return $deps;
	}
	
	public function logout() {
		$_SESSION['user_is_logged'] = FALSE;
		session_unset();
		session_destroy();
	}
}