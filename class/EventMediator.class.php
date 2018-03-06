<?php

class EventMediator {
	
	private $session;
	private $module;
	private $action;
	
	public function __construct() {
	}
		
	public function main( Session $session ) {
		$this->action = '';
		$this->session = $session;
		$this->requestHandler();

		$GUI = new GUI( $this->session );
		$GUI->render( $this->module, $this->action );
	}
	
	public function requestHandler() {
		if ( $this->session->isNotLogged() && $this->isLoginFormSubmitted() ) {
			$this->loginHandler();
		} elseif ( $this->session->isNotLogged() ) {
			$this->module = LOGIN;		
		} elseif( $this->isLogoutSubmitted() ) {
			$this->session->logout();	
			$this->redirectToRoot();
		} elseif ( $this->isFormSubmitted() ) {
			$this->formHandler();		
		} elseif( $this->isModuleSubmitted() ) {
			$this->moduleSelector();
		} else {
			$this->module = FRONT_PAGE;
		}
	}
	
	public function isLoginFormSubmitted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST' && $_REQUEST['form'] == LOGIN;
	}
	
	public function loginHandler() {
		$login = new Login();
		try {
			$login->validation( $_REQUEST['username'], $_REQUEST['password'] );
			$this->session->setGlobals( $_REQUEST['username'] );
			$page = FRONT_PAGE;
		} catch (Exception $e) {
			echo $e->getMessage();
			$page = LOGIN;
		} finally {
			$this->module = $page;
		}
	}
	
	public function isLogoutSubmitted() {
		if( isset( $_REQUEST['logout'] ) && $_REQUEST['logout'] == TRUE ) {
			return TRUE;
		}
	}
	
	public function redirectToRoot() {
		header( 'refresh:0;URL=' . ROOT );
		exit;
	}
	
	public function isFormSubmitted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	public function formHandler() {
		$form = new Form();
		$form->formManager();
		$this->module = $form->getModule();
		$this->action = $form->getAction();
	}
	
	public function isModuleSubmitted() {
		return isset( $_GET['module'] );
	}
	
	public function moduleSelector() {
		$this->module = $this->getModule();
		$privilegeToVerify = $this->module;
		try {
			$this->hasModuleAccessPrivilege( $this->module );
		
			if( $this->isActionSubmitted() ) {
				$this->actionHandler();
			}	
		} catch (Exception $e) {
			echo $e->getMessage();
			return 403;
		}
	}
	
	public function getModule() {
		return $_GET['module'];
	}
	
	public function hasModuleAccessPrivilege( $module ) {
		if( !$this->isSystemCoreFunction( $module ) && !$this->session->hasPrivilege( $module ) ) {
			throw new Exception( 'Non si dispone dei privilegi necessari per accedere al modulo ' . $module );
		}
	}
	
	public function isSystemCoreFunction( String $page ) {
		foreach( SYSTEM_CORE_FUNCIONS as $v1 ) {
			foreach ( $v1 as $v2 ) {
				if( strpos( $v2, $page ) !== FALSE ) return TRUE;
			}
		}
	}

	public function isActionSubmitted() {
		return isset( $_GET['action'] );
	}
	
	public function getAction() {
		return $_GET['action'];
	}
	
	public function actionHandler() {
		$this->action = $this->getAction();
		switch ( $this->action ) {
			case 'open':
			case 'deleteTarget':
			case 'deleteTaskAttachment':
			case 'deleteUserJob':
			case 'deleteUser':
				try {
					$action = $this->action;
					$this->$action();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				break;
			default: 
			//	throw new Exception( __FUNCTION__ . ' default value.');
				break;
		}
	}
	
	public function open() {
		$module = $this->module;
		$moduleManager = new $this->module();
		
		if( $module != INDICATOR && $this->session->hasPrivilege( $this->module . '-' . EDIT_PAGE ) ) {
			$this->action = EDIT_PAGE;
		} elseif( $module == INDICATOR && $moduleManager->isNotComplete() && $this->session->hasPrivilege( $this->module . '-' . EDIT_PAGE ) ) {
			$this->action = EDIT_PAGE;
		} elseif( $this->session->hasPrivilege( $this->module . '-' . VIEW_PAGE ) ) {
			$this->action = VIEW_PAGE;
		} else {
			throw new Exception( 'Non si dispone dei privilegi necessari per eseguire l\'azione.');
		}
	}
	
	public function deleteTaskAttachment() {
		$taskManager = new Task();
		if( $this->session->hasPrivilege( $this->module . '-edit' ) && $taskManager->isNotComplete() ) {
			$indicator = new Indicator();
			$indicator->fileDelete( $_GET['target'], $_GET['code'], $_GET['file']);
			$this->action = TASK_EDIT;
		} elseif ( $this->session->hasPrivilege( $this->module . '-edit' ) && $taskManager->isComplete() ) {
			throw new Exception('Attività conclusa: non è possibile apportare ulteriori modifiche.');
		}
	}
	
	public function deleteUserJob() {
		$user = new User();
		if( $this->session->hasPrivilege( USER_EDIT_FEATURE ) ) {
			$user->userjobDelete( $_GET['jobID'] );
			$this->action = EDIT_PAGE;
		}
	}
	
	public function deleteUser() {
		$user = new User();
		if( $this->session->hasPrivilege( USER_EDIT ) ) {
			$user->userDelete( $_GET['user'] );
			$this->action = USER_INDEX;
		}
	}
}