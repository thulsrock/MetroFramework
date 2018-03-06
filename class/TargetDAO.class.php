<?php

class TargetDAO /* extends Element */ {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}

	public function list( $departments = NULL ) {
		$query = '';
		if( !is_array( $departments ) ) $departments = array( $departments );
		for( $i = 0; $i < count( $departments ); $i++ ) {
			$query .=	"SELECT t.code, t.department as IDDepartment, t.name, t.description, t.weight, t.startDate, t.endDate, d.name as department
						FROM target t
						JOIN department d ON t.department = d.ID";
			if( isset( $departments ) && $departments[$i] != NULL ) $query .= " WHERE t.department = $departments[$i] ";
			if( count( $departments ) > 1 && $i < count( $departments ) -1 ) {
				$query .= ' UNION ';
			}
		}

		$query .= " ORDER BY t.code, d.name ASC ";
		
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute();
		
		return $stmt->fetchAll( PDO::FETCH_OBJ );
	}
	
	public function upload( array $element, String $status ) {
		
		if( $status == 'new' ) {
			$query = 'INSERT INTO target VALUES ( :code, :department, :name, :description, :weight, :startDate, :endDate )';
		} else {
			$query = 'UPDATE target SET department=:department, name=:name, description=:description, weight=:weight, startDate=:startDate, endDate=:endDate WHERE code=:code';
		}

		$stmt = $this->pdo->prepare ( $query );	
		$stmt->bindParam(':code', $element['code'], PDO::PARAM_STR);
		$stmt->bindParam(':department', $element['department'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $element['name'], PDO::PARAM_STR);
		$stmt->bindParam(':description', $element['description'], PDO::PARAM_STR);
		$stmt->bindParam(':weight', $element['weight'], PDO::PARAM_INT);	
		$stmt->bindParam(':startDate', $element['startDate'], PDO::PARAM_STR);
		$stmt->bindParam(':endDate', $element['endDate'], PDO::PARAM_STR);
		
		try {
			$stmt->execute();
			echo $_SESSION['notice'] = 'Obiettivo registrato.';
		} catch ( Exception $e ) {
			$this->pdo->rollBack();
			echo $e->getMessage();
			throw new Exception('Registrazione Obiettivo fallita.');
		}
	}
	
	public function getTargetDetailsFromCode( $code ) {
		try {
			$query =	"SELECT *
						FROM target
						WHERE code = :code";
			$stmt = $this->pdo->prepare ( $query );
			$stmt->bindParam(':code', $code, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch( PDO::FETCH_OBJ );
			
			$result->staff = $this->getStaff($target, $code);
		} catch (Exception $e) {
			echo 'Dettagli non trovati.';
		} finally {
			return $result;
		}
	}

}