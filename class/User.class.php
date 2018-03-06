<?php

class User extends UserDAO {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getJobsAndFeaturesFromUserID( int $userID ) {
		try {
			$jobManager = new JobDAO();
			$tmpJobs = $jobManager->getJobsFromUserID( $userID );
			
			$jobs = array();
			
			foreach( $tmpJobs as $tmpJob ) {
				$jobs[$tmpJob->ID] = new stdClass();
				
				$jobs[$tmpJob->ID]->department = $tmpJob->department;
				$jobs[$tmpJob->ID]->startDate = $tmpJob->startDate;
				$jobs[$tmpJob->ID]->endDate = $tmpJob->endDate;
				
				$featureManager = new FeatureDAO();
				$jobsFeature = $featureManager->getFeatureFromJobID( $tmpJob->ID );
				
				try {
					foreach( $jobsFeature as $feature ) {
						$jobs[$tmpJob->ID]->features[] = $feature->code;
					}
				} catch (Exception $e) {
					return NULL;
				}
			}
		} catch (Exception $e) {
			return NULL;
		}
		return $jobs;
	}
	
	public function passwordValidation( $username, $oldPassword, $newPassword, $confirmNewPassword ) {
		
		/*
		 $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/m';
		 
		 (?=.*\d) Atleast a digit
		 (?=.*[a-z]) Atleast a lower case letter
		 (?=.*[A-Z]) Atleast an upper case letter
		 (?!.* ) no space
		 (?=.*[^a-zA-Z0-9]) at least a character except a-zA-Z0-9
		 .{8,16} between 8 to 16 characters
		 
		 */
		if( $newPassword != $confirmNewPassword ) {
			throw new Exception('<i>Nuova Password</i> e <i>Conferma nuova password</i> non coincidono.');
		}
		if( strlen( $newPassword ) < PASSWORD_MIN_LENGTH || strlen( $newPassword ) > PASSWORD_MAX_LENGTH ) {
			throw new Exception('La nuova password deve avere almeno n.' . PASSWORD_MIN_LENGTH . ' ed al massimo n.' . PASSWORD_MAX_LENGTH . ' caratteri.');
		}
		if( !preg_match("/[A-Z]/", $newPassword ) ) {
			throw new Exception('La nuova password deve contenere almeno una lettera maiuscola.');
		}
		if( !preg_match("/[a-z]/", $newPassword ) ) {
			throw new Exception('La nuova password deve contenere almeno una lettera minuscola.');
		}
		if ( !preg_match("/\d/", $newPassword ) ) {
			throw new Exception('La nuova password deve contenere almeno un numero.');
		}
		if ( !preg_match("/[^a-zA-Z0-9]/", $newPassword ) ) {
			throw new Exception('La nuova password deve contenere almeno un carattere speciale.');
		}
		return TRUE;
	}
	
	public function registerUserInit( array $newUser ) {
		if( $this->verifyAttributes( $newUser ) == TRUE ) {
			if( $this->registerUserDetails() ) {
				return TRUE;
			}
		} else return FALSE;
	}
	
	public function verifyAttributes( array $newUser ) {
		$userKeys = $this->getUserAttributes();
		
		if ( $this->verifyInputFormAttributes( $userKeys, $newUser ) ) {
			$this->user = $newUser;
			return TRUE;
		} else return FALSE;
	}
	
	public function verifyInputFormAttributes( array $userKeys, array $newUser ) {
		/**
		 * Matches the user input form's fields with the attributes retrieved from the DB
		 */
		/*
		 foreach ( $userKeys as $value ) {
		 //		if( $value == 'username' || $value == 'email' || $value == 'SSN' || $value == 'serialNumber' ) continue;
		 if( !array_key_exists( $value, $newUser ) ) {
		 $_SESSION['error'][] = "Campo " . $value. " nullo";
		 $return = FALSE;
		 }
		 if( $this->fieldIsEmpty( $newUser[$value] ) ) {
		 $_SESSION['error'][] = "Campo " . $value. " non compilato";
		 $return = FALSE;
		 }
		 }*/
		//return $return;
		return TRUE;
	}
	
	public function fieldIsEmpty( $userValue ) {
		if( !isset( $userValue ) || $userValue == '' ) return TRUE;
		else return FALSE;
	}
	
	public function fieldIsNotValid( $userValue ) {
		return FALSE;
	}
	
	public function update( array $userDetails ) {
		$return = FALSE;
		if( ( $userDetails['startDate'] != NULL || $userDetails['endDate'] != NULL ) && $userDetails['department'] != NULL ) {
			if( $this->userjob_update( $userDetails ) ) {
				$return = TRUE;
			}
		}
		if( $this->user_update( $userDetails ) ) {
			$return = TRUE;
		}
		return $return;
	}
	
	public function updateUserjobDetails( array $userjobDetails ) {
		$return = FALSE;
		if( $this->deleteFeatures( $userjobDetails['userjob'] ) ) {
			foreach( $userjobDetails['userjob_feature'] as $feature ) {
				if( !$this->insertFeature( $userjobDetails['userjob'], $feature) ) return FALSE;
			}
			$return = TRUE;
		}
		return $return;
	}
	
}