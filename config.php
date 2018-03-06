<?php

// db parameters
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'MetroFramework');
define('DB_CHARSET', 'UTF8');

// URLs
define( 'ROOT', 'http://' . $_SERVER['SERVER_NAME'] .'/MetroFramework/' );
define( 'CLASS_DIR', 'class/' );
define( 'SOURCES', 'sources/' );
define( 'VENDOR', 'vendor/');
define( 'DOCUMENTS', 'docs/' );
define( 'JQUERY', SOURCES . 'JQuery/jquery-3.2.1.min.js' );
define( 'JQUERYUI', SOURCES . 'JQuery/jquery-ui.min.js' );
define( 'IMAGES', SOURCES . 'images/' );

define( 'INTERFACE_DIR', 'interface/' );

// Components
define( 'CONTENT_DIR', 'content/' );
define( 'COMPONENT_DIR', CONTENT_DIR. 'component/' );
define( 'MODULE_DIR', CONTENT_DIR. 'modules/' );
define( 'STYLE_DIR', CONTENT_DIR . 'style/');

define( 'UPLOAD_ROOT', 'D:/www/MetroFramework/uploads/' );
define( 'UPLOAD_DIR', 'uploads/' );
define( 'UPLOAD_REPORTS', 'D:/www/MetroFramework/reports/');
define( 'REPORT_DIR', 'reports/' );

// Debug & Test phase
define( 'DEBUG', FALSE );
define( 'ERROR_REPORTING', -1 );

define( 'TOTAL_ATTEMPT', 5 );
define( 'PASS_DEFAULT', "Provincia1" );
define( 'PASSWORD_MIN_LENGTH', 8 );
define( 'PASSWORD_MAX_LENGTH', 32 );

// Titles
define( 'COMPANY_NAME', 'Città Metropolitana di Bari' );
define( 'COMPANY_SUBTITLE', 'Ufficio Informatizzazione e Statistica' );
define( 'SITENAME', 'Strumento di analisi per il Nucleo di Gestione' );

define( 'DEFAULT_START_DATE', '2017-01-01' );
define( 'DEFAULT_END_DATE', '2019-12-31' );

define( 'CURRENT_YEAR', '2018');

// Core functions

define( 'SYSTEM_CORE_FUNCIONS', array(
	'password'	=>	array(
		'Cambio password'	=>	'module=password',	
	),
	'manual'	=>	array(
		'Documentazione'	=>	'file=Documentazione.pdf',
	),
	'PEG'	=>	array(
				'Peg'		=>	'file=PEG_2017.pdf',
	),
	'logout'	=>	array(
		'Logout'			=>	'logout=true',
	)
) );

# MODULES

define( 'INDICATOR', 'indicator');
define( 'TASK', 'task');
define( 'TARGET', 'target');
define( 'DEPARTMENT', 'department');

#ACTIONS

define( 'OPEN', 'open');
define( 'DELETE_TARGET', 'deleteTarget');
define( 'DELETE_TASK_ATTACHMENT', 'deleteTaskAttachment');
define( 'DELETE_USER_JOB', 'deleteUserJob');
define( 'DELETE_USER', 'deleteUser');


#PAGES AND FORMS

define( 'LOGIN', 'login');
define( 'FRONT_PAGE', 'front-page');
define( '403', '403');
define( '404', '404');

define( 'HEADER_LOGO', IMAGES . 'logo.png');
define( 'PDF_LOGO', IMAGES . 'fullLogo.png');
define( 'HEADER_LOGO_TEXT', IMAGES . 'logoText.png');
define( 'PDF_TASK_LOGO', IMAGES . 'pdf.png');
define( 'PDF_TASK_MULTIPLE_LOGO', IMAGES . 'pdf_multiple.png');

define( 'EDIT_PAGE', 'edit');
define( 'VIEW_PAGE', 'view');

define( 'TARGET_INDEX', NULL);
define( 'TARGET_NEW', 'target-new');
define( 'TARGET_EDIT', 'target-edit');

define( 'TASK_INDEX', NULL);
define( 'TASK_NEW', 'task-new' );
define( 'TASK_EDIT', 'task-edit' );

define( 'INDICATOR_INDEX', NULL);
define( 'INDICATOR_EDIT', 'indicator-edit');

define( 'USER_INDEX', NULL);
define( 'USER_NEW', 'user-new');
define( 'USER_EDIT', 'user-edit');
define( 'USER_EDIT_FEATURE', 'user-edit-feature');

define( 'PASSWORD_CHANGE', 'password-change');

#TITLE LINKS
define( 'TARGETS_BY_DEPARTMENT', 'Lista degli obiettivi del Servizio' );
define( 'TASKS_BY_TARGET', 'Lista delle attività dell\'obiettivo' );



