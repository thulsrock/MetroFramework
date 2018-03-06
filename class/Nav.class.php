<?php

class Nav {
	
	private $session;
	private $nav;
	private $module;
	
	public function __construct( Session $session ) {
		$this->session = $session;
		$this->nav = new stdClass();
		$this->setActiveModule();
		$this->build();
	}
	
	public function setActiveModule() {
		if( isset( $_GET['module '] ) ) {
			$this->module = $_GET['module'];
		} else {
			$this->module = '';
		}
	}

	public function build() {
		$jobs = $this->session->getActiveUserJobs();

		$feature = new FeatureDAO();
		$featureAreaList = $feature->getAreaList();

		foreach ( $featureAreaList as $area ) {
			if ( $this->session->hasPrivilege( $area->name ) && $area->hidden != TRUE ) {
				$this->nav->{$area->description} = 'module=' .  $area->name;
			}		
		}
		$this->setSystemCoreLinks();
	}

	public function setSystemCoreLinks() {
		foreach ( SYSTEM_CORE_FUNCIONS as $v1 ) {
			foreach ( $v1 as $k2 => $v2 ) {
				$this->nav->$k2 = $v2;
			}
		}
	}
	
	public function render() {
		$html = '<nav class="align_center noprint wrap" style="margin-top: 10px;">';
		foreach ( $this->nav as $key => $value ) {
			if( strpos( $value, 'file=' ) !== FALSE ) {
				$value = str_replace( 'file=', '', $value );
				$html .= '<a href="'.htmlspecialchars( ROOT . DOCUMENTS . $value ).'" class="transition" >'.htmlspecialchars( $key ).'</a>';
			} else {
				$selected = ( strpos( $_SERVER['REQUEST_URI'], $value ) !== FALSE ) ? 'active': NULL;
				$html .= '<a href="'.htmlspecialchars( $_SERVER['PHP_SELF'] . '?' . $value ).'" class="transition '.$selected.'">'.htmlspecialchars( $key ).'</a>';
			}
		}
		echo $html .='</nav>';
	}
}