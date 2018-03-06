<?php

class Form {
	
	private $module;
	private $action;
	
	public function __construct() {
		$this->form = $_POST['form'];
		$form = explode('-', $_POST['form']);
		$this->module = $form[0];
		$this->action = $form[1];
	}
	
	public function formManager() {
		try {
			$this->formRequestHandler();
			unset( $_REQUEST );
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function formRequestHandler() {
		switch( $this->form ) {
			case TARGET_NEW:
			case TARGET_EDIT:
				$target = new Target();
				$target->registerTarget( $_REQUEST, $this->action );
				if( $this->form == TARGET_EDIT ) $this->action = TARGET_INDEX; 
				break;
			case TASK_NEW:
			case TASK_EDIT:
				$task = new Task();
				$task->registerTask( $_REQUEST, $this->action );
				if( $this->form == TASK_EDIT ) $this->action = TASK_INDEX; 
				break;
			case INDICATOR_EDIT:
				$indicator = new Indicator();
				$indicator->register( $_REQUEST );
				$this->module = TASK;
				$this->action = INDICATOR_INDEX;
				break;
			case USER_NEW:
				$user = new User();
				$user->registerUserInit( $_REQUEST );
				break;
			case USER_EDIT:
				$user = new User();
				$user->update( $_REQUEST );
				$this->action = USER_INDEX;
				break;
			case USER_EDIT_FEATURE:
				$user = new User();
				$user->updateUserjobDetails( $_REQUEST );
				$this->action = USER_EDIT;
				break;
			case PASSWORD_CHANGE:
				$user = new User();
				$user->passwordChange();
				$this->module = FRONT_PAGE;
				break;
			default:
				$this->module = FRONT_PAGE;
				throw new Exception( 'Default per funzione ' . __FUNCTION__ );
				break;
		}
	}
	
	public function getModule() {
		return $this->module;
	}
	
	public function getAction() {
		return $this->action;
	}
}