<?php

class JobDAO {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
		
	public function getJobsFromUserID( int $userID ) {
		$query =	'SELECT uj.ID, uj.department, uj.startDate, uj.endDate
					FROM userjob uj
					JOIN user u ON uj.user = u.ID
					WHERE u.ID = ?';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $userID ] );
		return $stmt->fetchALL ( PDO::FETCH_OBJ );
	}
	
	public function getUserIDFromJobID( $jobID ) {
		$query = 'SELECT u.ID FROM user u JOIN userjob uj ON u.ID = uj.user WHERE uj.ID = ?';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $jobID ] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $result->ID;
	}
	
}