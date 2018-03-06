<?php

class Element {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
	
	public function register( array $element ) {
		try {
			$this->validate( $element );
			$sanitizedElement = $this->sanitize( $element );
			$this->upload( $sanitizedElement );
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
	}
	
	public function validate( array $element ) {
		foreach ( $element as $key => $value ) {
			$this->validateField( $key, $value);
		}
	}
	
	public function validateField( $key, $value ) {
		if( $value == NULL ) {
			throw new Exception( 'Campo ' . $key . ' nullo.' );
		}
	}
	
	public function sanitize( array $element ) {
		$sanitizedElement = [];
		foreach( $element as $key => $value ) {
			$sanitizedElement[$key] = $this->sanitizeField( $value );
		}
		return $sanitizedElement;
	}
	
	public function sanitizeField( $field ) {
		$sanitizedField = preg_replace( '/\r|\n/', ' ', $field );
		return $sanitizedField;
	}
	
	public function upload( array $element ) {
		try {
			
		} catch (Exception $e) {
			$this->pdo->rollBack();
			throw new Exception('Registrazione Attività fallita.');
		}
	}
	
}