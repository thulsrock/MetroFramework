<?php

class Target extends TargetDAO {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function registerTarget( array $newTarget, $status ) {
		try {
			$this->validateNewTarget( $newTarget );
			$sanitizedTarget = $this->sanitizeNewTarget( $newTarget );
			$this->upload( $sanitizedTarget, $status );
		} catch (Exception $e) {
			$this->pdo->rollBack();
			echo $e->getMessage();
		}
	}
	
	public function validateNewTarget( array $newTarget ) {
		foreach ( $newTarget as $key => $value ) {
			$this->validateNewTargetField($key, $value);
		}
	}
	
	public function validateNewTargetField( $key, $value ) {
		if( $value == NULL ) throw new Exception( 'Campo ' . $key . ' nullo.' );
	}
	
	public function sanitizeNewTarget( array $newTarget ) {
		$sanitizedTarget = [];
		foreach( $newTarget as $key => $value ) {
			$sanitizedTarget[$key] = preg_replace( '/\r|\n/', ' ', $value );
		}
		return $sanitizedTarget;
	}
	
}