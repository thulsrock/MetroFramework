<?php

class Task extends TaskDAO {
	
	private $target;
	private $code;
	
	public function __construct() {
		parent::__construct();
	}
	
	public function registerTask( array $element, $status ) {
		try {
			$this->validate( $element );
			$sanitizedTask = $this->sanitizeNewTask( $element );
			$this->upload( $sanitizedTask, $status );
		} catch (Exception $e) {
			echo $e->getMessage();
			throw new Exception('Registrazione attività fallita.');
		}
	}
	
	public function validate( array $element ) {
		foreach ( $element as $key => $value ) {
			$this->validateField($key, $value);
		}
	}
	
	public function validateField( $key, $value ) {
		if( $key == 'endDate' ) return;
		elseif ( $value == NULL ) throw new Exception( 'Campo ' . $key . ' nullo.' );
	}
	
	public function sanitizeNewTask( array $newTask ) {
		$sanitizedTask = [];
		foreach( $newTask as $key => $value ) {
			$sanitizedTask[$key] = preg_replace( '/\r|\n/', ' ', $value );
		}
		return $sanitizedTask;
	}
}