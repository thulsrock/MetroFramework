<?php

class UserDAO {
	
	private $pdo;
	private $user;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}

	public function getUserDetailFromID( int $userID ) {
		$query = 'SELECT * FROM user WHERE ID = ?';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $userID ] );
		return $stmt->fetch ( PDO::FETCH_OBJ );
	}

	public function getJobDetailFromID( $jobID ) {
	    $query =	'SELECT department, startDate, endDate
					FROM userjob
					WHERE ID = ?';
	    $stmt = $this->pdo->prepare ( $query );
	    $stmt->execute ( [ $jobID ] );
	    return $stmt->fetch ( PDO::FETCH_OBJ );
	}
	
	public function passwordChange() {
		$username = isset( $_REQUEST['username'] ) ? $_REQUEST['username'] : $_SESSION['username'];
		$oldPassword = $_REQUEST['oldPassword'];
		$newPassword = $_REQUEST['newPassword'];
		$confirmNewPassword = $_REQUEST['confirmNewPassword'];
					
		try {
			$this->verifyPassword( $username, $oldPassword );
			!$this->passwordValidation($username, $oldPassword, $newPassword, $confirmNewPassword);
		} catch (Exception $e) {
			echo $e->getMessage();
			return FALSE;
		}
					
		$password = password_hash( $_REQUEST['newPassword'], PASSWORD_BCRYPT, array('cost' => 13) );	
		$query = 'UPDATE user SET password = :password WHERE username = :username';
		
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();
		
		if( !$stmt ) {
			print_r($dbh->errorInfo());
			$_SESSION['error'][] = "Aggiornamento della password fallito.";
			return FALSE;
		} else {
			$_SESSION['notice'][] = 'Aggiornamento della password riuscito.';
			unset( $_SESSION['error'] );
			return TRUE;
		}
	}
	

	
	
	public function userList() {
		$query = "SELECT * FROM user ORDER BY surname ASC";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_OBJ );
		if( $stmt->rowCount() > 0 ) return $result;
		else return NULL;
	}
	
	public function getFeatureArea( $job ) {
		$query = 	"SELECT DISTINCT fa.name name, fa.description description
					FROM feature_area fa
					JOIN feature f ON fa.name = f.area
					JOIN userjob_feature uf ON f.code = uf.feature
					JOIN userjob uj ON uf.userjob = uj.ID 
					WHERE uj.ID = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [$job] );
		$result = $stmt->fetchAll( PDO::FETCH_OBJ );
		if( $stmt->rowCount() > 0 ) return $result;
		else return NULL;
	}
	
	public function getUserAttributes() {
		$query = 'DESCRIBE user';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute();
		$result = $stmt->fetchAll( PDO::FETCH_COLUMN );
		unset($result['ID']);
		$newResult = array();
		foreach( $result as $key => $value ) {
			if( $key == 'ID' ) continue;
			$newResult[$key] = $value; 
		}
		return $newResult;
	}
	
	public function registerUserDetails() {
		$encryptedPassword = password_hash( $this->user['password'], PASSWORD_BCRYPT, array( 'cost' => 13 ) );
		
		$query =	'INSERT INTO user ( SSN, username, password, serialNumber, name, surname, email )
					VALUES ( :ssn, :username, :password, :serialNumber, :name, :surname, :email )';
		
		$stmt = $this->pdo->prepare ( $query );
		
		$stmt->bindParam(':ssn', $this->user['ssn'], PDO::PARAM_STR);
		$stmt->bindParam(':username', $this->user['username'], PDO::PARAM_STR);
		$stmt->bindParam(':password', $encryptedPassword, PDO::PARAM_STR);
		$stmt->bindParam(':serialNumber', $this->user['serialNumber'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $this->user['name'], PDO::PARAM_STR);
		$stmt->bindParam(':surname', $this->user['surname'], PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->user['email'], PDO::PARAM_STR);
		$stmt->execute();
		
		#TODO email sender
		if( isset($this->user['email'])) {
	//		$this->sendUserCredentials();
		}

		if( !$stmt ) {
			print_r($dbh->errorInfo());
			throw new Exception( 'Registrazione utente fallita.' );
		} else {
			return TRUE;
		}
	}
	
	public function user_update( array $userDetails ) {
		$query =	"UPDATE user
					SET username = :username,
						name = :name,
						surname = :surname,
						email = :email,
						SSN = :SSN,
						serialNumber = :serialNumber,
						disabled = :disabled
	
					WHERE ID = :ID";
		
		$stmt = $this->pdo->prepare ( $query );
		
		$stmt->bindValue(':username', $userDetails['username'], PDO::PARAM_STR);
		$stmt->bindValue(':name', $userDetails['name'], PDO::PARAM_STR);
		$stmt->bindValue(':surname', $userDetails['surname'], PDO::PARAM_STR);
		$stmt->bindValue(':email', $userDetails['email'], PDO::PARAM_STR);
		$stmt->bindValue(':SSN', $userDetails['SSN'], PDO::PARAM_INT);
		$stmt->bindValue(':serialNumber', $userDetails['serialNumber'], PDO::PARAM_INT);
		$stmt->bindValue(':disabled', $userDetails['disabled'], PDO::PARAM_INT);
		$stmt->bindValue(':ID', $userDetails['ID'], PDO::PARAM_INT);
		$stmt->execute();
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( !$stmt ) {
			throw new Exception( 'Dettagli utente non aggiornati.' );
		} else {
			return TRUE;
		}
	}
	
	public function userjob_update( array $userDetails ) {
		if( !isset( $userDetails['department'] ) ) return TRUE;
	
		$query = 	"INSERT IGNORE INTO userjob ( user, department, startDate, endDate )  
					VALUES ( :userID, :department, :startDate, :endDate )";
		$stmt = $this->pdo->prepare ( $query );
		
		$stmt->bindValue(':userID', $userDetails['ID'], PDO::PARAM_INT);
		$stmt->bindValue(':department', $userDetails['department'], PDO::PARAM_INT);
		$stmt->bindValue(':startDate', $userDetails['startDate'], PDO::PARAM_STR);
		$stmt->bindValue(':endDate', $userDetails['endDate'], PDO::PARAM_STR);
		$stmt->execute();
		
		if( !$stmt ) {
			throw new Exception( 'Dettagli incarico utente non aggiornati.' );
		} else {
			return TRUE;
		}
	}
	
	public function userjobDelete( $userjobID ) {
		$query = 	"DELETE FROM userjob WHERE ID = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute( [ $userjobID ] );
		
		if( !$stmt ) {
			throw new Exception( 'Dettagli incarico utente non aggiornati.' );
		} else {
			return TRUE;
		}
	}
	
	public function insertFeature( $userjob, $feature ) {
		$query = "INSERT IGNORE INTO userjob_feature VALUE (:userjob, :feature )";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindValue(':userjob', $userjob, PDO::PARAM_INT);
		$stmt->bindValue(':feature', $feature, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt ) return TRUE;
		else return FALSE;
	}
	
	public function deleteFeatures( $userjob ) {
		$query = "DELETE FROM userjob_feature where userjob = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute( [ $userjob ] );
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt ) return TRUE;
		else return FALSE;
	}
	
	public function userExists( String $username ) {
		$stmt = $this->pdo->prepare ( "SELECT count(*) as count FROM user WHERE username = ?" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $result->count != 1 ) throw new Exception( 'Utente non censito.' );
	}
	
	public function verifyPassword( String $username, String $password ) {
		$stmt = $this->pdo->prepare ( "SELECT password FROM user WHERE username = ?" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if ( !password_verify( $password, $result->password ) ) throw new Exception( 'La password non è corretta.' );
		// To register the first user, print the password than copy/paste to the DB
		/*
		 $password = password_hash($post['password'], PASSWORD_BCRYPT, array('cost' => 13));
		 echo $password;
		 die();
		 */
	}
	public function userIsActive( String $username ) {
		$stmt = $this->pdo->prepare ( "SELECT * FROM user WHERE username = ? AND disabled = 0" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt->rowCount() != 1 ) {
			throw new Exception('Account utente disabilitato.');
		}
	}
	
	public function getUserIDFromUsername( String $username ) {
		$stmt = $this->pdo->prepare ( "SELECT ID FROM user WHERE username = ?" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $result->ID;
	}

	public function userDelete( int $userID ) {
		$stmt = $this->pdo->prepare ( "DELETE FROM user WHERE ID = ?" );
		$stmt->execute ( [$userID] );
		if( ! $stmt ) {
			throw new Exception('Utente non cancellato.');
		}
	}
}