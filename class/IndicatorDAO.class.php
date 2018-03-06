<?php

class IndicatorDAO {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
	
	public function isComplete( String $target, String $code ) {
		$query =	"SELECT *
					FROM task
					WHERE target = :target AND code = :code AND progression = 100" ;
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->bindParam(':code', $code, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );
		if ( $stmt->rowCount() == 1 ) return TRUE;
		else return FALSE;
	}
	
	public function update( array $newIndicator ){
		if( $newIndicator['progression'] == 100 ) {
			$newIndicator['closedDate'] = date("Y-m-d");
		} else {
			$newIndicator['closedDate'] = '';
		}
		
		$query =	"UPDATE task
					SET actualResult = :actualResult,
						progression = :progression,
						difficulty = :difficulty,
						closedDate = :closedDate
					WHERE target = :target AND
						code = :code";
		
		$stmt = $this->pdo->prepare ( $query );
		
		$stmt->bindValue(':actualResult', $newIndicator['actualResult'], PDO::PARAM_INT);
		$stmt->bindValue(':progression', $newIndicator['progression'], PDO::PARAM_INT);
		$stmt->bindValue(':difficulty', $newIndicator['difficulty'], PDO::PARAM_STR);
		$stmt->bindValue(':closedDate', $newIndicator['closedDate'], PDO::PARAM_STR);
		$stmt->bindValue(':target', $newIndicator['target'], PDO::PARAM_STR);
		$stmt->bindValue(':code', $newIndicator['code'], PDO::PARAM_STR);
		$stmt->execute();
		
		$mod = $this->updateStaff( $newIndicator['target'], $newIndicator['code'], $newIndicator['staff'] );
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt->rowCount() > 0 || $mod == TRUE ) {
			return TRUE;
		} else {
			throw new Exception( 'Nessuna modifica apportata.' );
			return FALSE;
		}
	}
	
	public function updateStaff( $target, $code, $staff ) {
		try {
			$this->pdo->beginTransaction();
			$this->deleteStaff($target, $code);
			
			foreach( $staff as $user ) {
				if ( $user == '' || $user == NULL ) continue;
				$query = "INSERT IGNORE INTO userjob_task ( target, code, user ) VALUES ( :target, :code, :user )";
				$stmt = $this->pdo->prepare( $query );
				
				$stmt->bindValue(':target', $target, PDO::PARAM_STR);
				$stmt->bindValue(':code', $code, PDO::PARAM_STR);
				$stmt->bindValue(':user', $user, PDO::PARAM_STR);
				$stmt->execute();
			}
			$this->pdo->commit();
			return TRUE;
		} catch( PDOException $e ) {
			$this->pdo->rollBack();
			echo $e->getMessage();
			return FALSE;
		}
	}
	
	public function deleteStaff( $target, $code ) {
		try {
			$query = "DELETE FROM userjob_task WHERE target = :target AND code = :code";
			$stmt = $this->pdo->prepare( $query );
			$stmt->bindValue(':target', $target, PDO::PARAM_STR);
			$stmt->bindValue(':code', $code, PDO::PARAM_STR);
			$stmt->execute();
			return TRUE;
		} catch ( PDOException $e ) {
			echo $e->getMessage();
			return FALSE;
		}
	}
	
}