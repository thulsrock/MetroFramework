<?php

class FeatureDAO {
	
	private $pdo;
	private $ID;
	private $code;
	private $name;
	private $description;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
	
	public function getFeatureFromJobID( int $jobID ) {
		$query = 	"SELECT f.code, f.name, f.description
					FROM feature f
					JOIN userjob_feature uf ON f.code = uf.feature
					JOIN userjob uj ON uj.ID = uf.userjob
					JOIN department d ON uj.department = d.ID
					WHERE uj.ID = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute( [$jobID] );
		return $stmt->fetchAll( PDO::FETCH_OBJ );
	}
	
	public function getFeatureFullList() {
		$query =	"SELECT *
					FROM feature
					ORDER BY area, name ASC";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ();
		return $stmt->fetchAll( PDO::FETCH_OBJ );
	}
	
	public function getFeatureByArea( $featureArea ) {
		$query =	'SELECT *
					FROM feature
					WHERE area = ?';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $featureArea ] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt->rowCount() > 0 ) {
			foreach( $result as $key => $value ) {
				$this->$key = $value;
			}
			return $this;	
		}
		else return NULL;
	}
	
	public function getAreaList() {
		$query =	'SELECT * FROM feature_area';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ();
		$result = $stmt->fetchAll( PDO::FETCH_OBJ );
		if( $stmt->rowCount() > 0 ) return $result;
		else return NULL;
	}
	
	public function getCode() {
		return $this->code;
	}
	public function getName() {
		return $this->name;
	}
	public function getDescription() {
		return $this->description;
	}
	
	public function isNav() {
		return strpos( $this->code, 'edit') !== FALSE;
	}
	
	public function isNotNav() {
		return strpos( $this->code, 'edit') !== TRUE;
	}
}