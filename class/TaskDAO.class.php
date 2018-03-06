<?php

class TaskDAO {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
	
	public function getTaskDetail( $target, $code ) {
		try {
			$query =	"SELECT *
						FROM task
						WHERE target = :target AND code = :code";
			$stmt = $this->pdo->prepare ( $query );
			$stmt->bindParam(':target', $target, PDO::PARAM_STR);
			$stmt->bindParam(':code', $code, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch( PDO::FETCH_OBJ );
		
			$result->staff = $this->getStaff($target, $code);
		} catch (Exception $e) {
			echo 'Staff non individuato.';
		} finally {
			return $result;
		}
	}
	
	public function getStaff( $target, $code ) {
		$query =	"SELECT DISTINCT user
					FROM userjob_task as ujt
					WHERE target = :target AND code = :code";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->bindParam(':code', $code, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetchAll( PDO::FETCH_OBJ );
		if ( $stmt->rowCount() >= 1 ) {
			return $result;
		} else return FALSE;
	}
	
	public function getDepartment( String $target ) {

		$query =	"SELECT DISTINCT d.ID as ID, d.name as name
					FROM department d
					JOIN target tg ON  d.ID = tg.department
					JOIN task t ON  tg.code = t.target
					WHERE t.target = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $target] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );

		if ( $stmt->rowCount() == 1 ) return $result;
		else return FALSE;
	}
	
	public function list( $departments = NULL, $target = NULL ) {
		$query = '';
		if( !is_array( $departments ) ) $departments = array( $departments );
		for( $i = 0; $i < count( $departments ); $i++ ) {
			$query .=	"SELECT t.target, t.code, t.description, tg.department, t.startDate, t.endDate, t.attendedResult, t.actualResult, t.progression, t.difficulty
					FROM task t
					JOIN target tg ON t.target = tg.code
					JOIN department d ON tg.department = d.ID";
			if( isset( $departments ) && $departments[$i] != NULL ) $query .= " WHERE tg.department = $departments[$i] ";
			if( isset( $target ) ) $query .= " AND tg.code = ? ";
			if( count( $departments ) > 1 && $i < count( $departments ) -1 ) {
				$query .= ' UNION ';
			}
		}
		
		$query .= " ORDER BY t.target ASC";

		$stmt = $this->pdo->prepare ( $query );
		
		if( isset( $target ) ) $stmt->execute( [ $target ] );
		else $stmt->execute();
		
		return $stmt->fetchAll( PDO::FETCH_OBJ );
	}
	
	public function upload( array $element, String $status ) {
		
		if( $status == 'new' ) {
			$query = 'INSERT INTO task ( target, code, name, description, startDate, endDate, attendedResult )
								VALUES ( :target, :code, :name, :description, :startDate, :endDate, :attendedResult )';
		} else {
			$query = 'UPDATE task SET code=:code, name=:name, description=:description, startDate=:startDate, endDate=:endDate, attendedResult=:attendedResult
					WHERE target=:target AND code=:newCode';
		}
		
		$stmt = $this->pdo->prepare ( $query );
		
		if( $status != 'new' ) {
			$stmt->bindParam(':newCode', $element['newCode'], PDO::PARAM_STR);
		}
		
		$stmt->bindParam(':target', $element['target'], PDO::PARAM_STR);
		$stmt->bindParam(':code', $element['code'], PDO::PARAM_STR);	
		$stmt->bindParam(':name', $element['name'], PDO::PARAM_STR);
		$stmt->bindParam(':description', $element['description'], PDO::PARAM_STR);
		$stmt->bindParam(':startDate', $element['startDate'], PDO::PARAM_STR);
		$stmt->bindParam(':endDate', $element['endDate'], PDO::PARAM_STR);
		$stmt->bindParam(':attendedResult', $element['attendedResult'], PDO::PARAM_INT);
		
		try {
			$stmt->execute();
			echo $_SESSION['notice'] = 'Attività ' . $element['target'] . $element['code'] . ' registrata.';
		} catch ( Exception $e ) {
			echo $e->getMessage();
			throw new Exception('Caricamento attività fallito.');
		}
	}
}