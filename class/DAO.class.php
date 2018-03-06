<?php

class Dao {
	
	private $pdo;
	
	public function __construct () {
		$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
		$dbopt = array (
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_PERSISTENT => true,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
		);
		
		try {
			$this->pdo = new PDO( $dsn, DB_USER, DB_PASS, $dbopt );
		} catch (PDOException $e) {
			echo "Connection error " . $e->getMessage() . "\n";
			exit;
		}
	}
	
	public function getPdo() {
		return $this->pdo;
	}
	
}
