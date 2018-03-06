<?php

class DepartmentDAO {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
	
	public function departmentList() {
		$query =	"SELECT * 
					FROM department
					WHERE ( endDate IS NULL OR
					endDate >= CURDATE() ) AND name LIKE 'Servizio%' 
					ORDER BY name ASC";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ();
		return $stmt->fetchAll ( PDO::FETCH_OBJ );
	}
	
	public function getNameFromID( $departmentID ) {
		$query = "SELECT name FROM department WHERE ID = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $departmentID ]);
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $result->name;
	}
	
	public function getStaff( $department, $startTask = NULL, $endTask = NULL ) {
		$query =	"SELECT uj.ID as userjob, u.name as name, u.surname as surname
					FROM user u
					JOIN userjob uj ON u.ID = uj.user
					JOIN department d ON uj.department = d.ID
					WHERE d.ID = :department AND uj.startDate <= :endTask AND ( uj.endDate >= :startTask OR uj.endDate = '0000-00-00' ) 
					ORDER BY u.surname ASC";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindValue(':department', $department, PDO::PARAM_INT );
		$stmt->bindValue(':startTask', $startTask, PDO::PARAM_STR);
		$stmt->bindValue(':endTask', $endTask, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll ( PDO::FETCH_OBJ );
		if( isset( $result ) && $result!= FALSE ) return $result;
		else return FALSE;
	}
}