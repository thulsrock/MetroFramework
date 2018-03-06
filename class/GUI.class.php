<?php

class GUI {
		
	private $session;
		
	public function __construct( Session $session ) {
		$this->session = $session;
	}

	public function render( String $page, String $action = NULL ) {
		try {
			ob_start();
			$this->getComponent('header');		
			$this->getBody( $page, $action );
			$this->getComponent('footer');

			$html = ob_get_clean();		
			echo $html;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function getBody( String $module, String $action = NULL ) {
		$file = $module;
		if( $this->isModule( $file ) ) {
			$file = $this->getModule( $module );
			if ( $action != '' ) {
				$file .= $action;
			} else $file .= 'index';
		} elseif ( file_exists( CONTENT_DIR . $file .'.php' ) ) {
			$file =  CONTENT_DIR . $file;
		}
		$this->getFile( $file );		
	}

	public function isModule( String $module ) {
		return file_exists( MODULE_DIR . $module .'/');
	}
	
	public function getModule( String $module ) {
		return  MODULE_DIR . $module .'/';
	}

	public function getComponent( String $module ){
		$this->getFile( COMPONENT_DIR . $module );
	}
	
	public function getFile( String $file ) {
		$filename = $file . '.php';
		if( !file_exists( $filename ) ) {
			throw new Exception( 'File ' . $file . ' non trovato.');
		} else {
			include ( $filename );
		}
	}
	
	public function getLoginPage(){
		try {
			$this->getComponent('header');
			$this->getFile( CONTENT_DIR . 'login' );
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function getMaterialIconStyle() {
		return './' . STYLE_DIR . 'materialicons.css';
	}
	
	public function getStyle() {	
		return './' . STYLE_DIR . 'style.css';
	}
	
	public function getPrintStyle() {
		return './' . STYLE_DIR . 'print.css';
	}
	
	public function buttonNewItem() {
		if( $this->session->hasPrivilege( $_GET['module'] .'-new' ) ) {
			//echo "<a id='buttonNewItem' class='round_border' href='". esc( $_SERVER['PHP_SELF'] ."?module=".$_GET['module'] ) . "&action=new'>Nuovo</a>";
			$this->getFile( COMPONENT_DIR . 'button_new_item' );
		}
	}

	public function buttonToTop() {
		$this->getFile( COMPONENT_DIR . 'button_to_top' );
	}
	
	public function buttonFormSend( $module, $action = NULL ) {
		$formValue = $module;
		if( isset( $action ) && $action != NULL ) {
			$formValue .= '-' . $action;
		}
		echo "<button id='button' class='formSend bold' type='submit' name='form' value=".$formValue." >Invia</button>";
	}
	
	public function navigationMenu() {
		if( $this->session->isLogged() ) {
			$nav = new Nav( $this->session );
			$nav->render();
		}
	}
}