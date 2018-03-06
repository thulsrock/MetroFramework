<?php

class Indicator extends IndicatorDAO {
	
	private $departmentName;
	private $departmentID;
	private $target;
	private $code;
	
	public function __construct( $target = NULL, $code = NULL ) {
		$this->target = $target? $target : $_GET['target'];
		$this->code = $code ? $code : $_GET['code'];
		parent::__construct();
	}
	
	public function register( array $newIndicator ) {
		try {
			$this->validation( $newIndicator );
			$this->update( $newIndicator );
			$this->file_receiver( $newIndicator['target'], $newIndicator['code'] );
			if( $this->isComplete($newIndicator['target'], $newIndicator['code'] ) ) {
				$this->buildPdfFiles( $newIndicator );
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function validation( array $newIndicator ) {
		if( !isset( $_FILES ) ) $files = NULL;	
		if( !isset( $_FILES['file']['name']) && !is_uploaded_file( $_FILES['file']['name']['tmp_name']) ) {
			throw new Exception( 'File non allegati - validazione fallita.' );
		} else {
			return TRUE;
		}
	}
	
	public function file_receiver( String $target, String $code ) {
		$upload = TRUE;
		
		for ( $i = 0; $i < count( $_FILES['file']['name'] ); $i++ ) {
			//Get the temp file path
			$tmpFilePath = $_FILES['file']['tmp_name'][$i];
			$filename = $_FILES['file']['name'][$i];
			
			//Make sure we have a filepath
			if ( $filename!= "" ) {
				
				$taskManager = new Task();
				$department = $taskManager->getDepartment( $target );
				
				$departmentPath = UPLOAD_ROOT . $department->name;
				$targetPath = $departmentPath . DIRECTORY_SEPARATOR . $target;
				$taskPath = $targetPath . DIRECTORY_SEPARATOR . $code;
				$targetfile = $taskPath . DIRECTORY_SEPARATOR . $filename;
				
				$fileExt = pathinfo( $targetfile, PATHINFO_EXTENSION );
				
				if( $fileExt != 'pdf' && $fileExt != 'p7m' ) {
					throw new Exception( 'Gli allegati devono avere estensione PDF e P7M.' );
					$upload = FALSE;
				}
				
				//Setup our new file path
				
				if( !file_exists( $departmentPath ) )	mkdir( $departmentPath );
				if( !file_exists( $targetPath ) ) 		mkdir( $targetPath );
				if( !file_exists( $taskPath ) )			mkdir( $taskPath );
				
				//Upload the file into the temp dir
				if ( !move_uploaded_file( $tmpFilePath, $targetfile) ) {
					throw new Exception( ' Caricamento di <b>' . $filename . '</b> fallito.' );
					$upload = FALSE;
					return $upload;
				}
			}
		}
		return $upload;
	}
	
	public function buildPdfFiles( array $indicator ) {
		$this->buildIndicatorSheet( $indicator );
		$this->buildIndicatorReport( $indicator['target'], $indicator['code'] );
	}
	public function buildIndicatorSheet( array $indicator ) {
		require_once SOURCES . 'pdfSheet.php';
	}
	
	public function buildIndicatorReport( String $target, String $code ) {

		$pdf = new \setasign\Fpdi\Fpdi();
		
		$taskManager = new Task();
		$task = $taskManager->getTaskDetail( $target, $code );
		$task->department = $taskManager->getDepartment( $target );
		$fileDir = UPLOAD_DIR . $task->department->name . DIRECTORY_SEPARATOR . $target . DIRECTORY_SEPARATOR . $code;
		
		$currentYearPath =	UPLOAD_REPORTS  . DIRECTORY_SEPARATOR . CURRENT_YEAR;
		$departmentPath =	$currentYearPath. DIRECTORY_SEPARATOR . $task->department->name;
		$targetPath = 		$departmentPath . DIRECTORY_SEPARATOR . $target;
		
		if( !file_exists( $currentYearPath ) )	mkdir( $currentYearPath );
		if( !file_exists( $departmentPath ) )	mkdir( $departmentPath );
		if( !file_exists( $targetPath ) ) 		mkdir( $targetPath );
		
		$mergedFilePath = $targetPath . DIRECTORY_SEPARATOR;
		$mergedFilename = $target . $code .'-report.pdf';
		
		$sourcefilePathList = '';
		
		if( file_exists( $fileDir ) ) {
			
			$files = scandir( $fileDir );
			
			$filename = $target . $code . '.pdf';		
			$filenamePosition = array_search( $filename, $files );
			
			unset( $files[$filenamePosition]);
			array_unshift($files, $filename);

			foreach ( $files as $file ) {
				if ($file == '.' || $file == '..' ) continue; 
				$pageCount = $pdf->setSourceFile($fileDir . DIRECTORY_SEPARATOR . $file);
				// iterate through all pages
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					// import a page
					$templateId = $pdf->importPage($pageNo);
					// get the size of the imported page
					$size = $pdf->getTemplateSize($templateId);
					// create a page (landscape or portrait depending on the imported page size)
					if ($size['width'] > $size['height']) {
						$pdf->AddPage('L', array($size['width'], $size['height']));
					} else {
						$pdf->AddPage('P', array($size['width'], $size['height']));
					}
					
					// use the imported page
					$pdf->useTemplate($templateId);
				}
			}
		}

		$pdf->Output($mergedFilePath . $mergedFilename,'F');
	}
	
	public function fileDelete( String $target, String $code, String $filename ) {
		$taskManager = new Task();		
		$department = $taskManager->getDepartment( $target );
		
		$filedir = UPLOAD_ROOT . $department->name . DIRECTORY_SEPARATOR . $target . DIRECTORY_SEPARATOR . $code;

		if ( ( $filename !== NULL ) && file_exists( $filedir . DIRECTORY_SEPARATOR . $filename ) ) {
			if( unlink ( $filedir . DIRECTORY_SEPARATOR . $filename ) ) {
				return TRUE;
			} else {
				throw new Exception( $filename . ' non rimosso dal server' );
			}
		}
	}
	
	public function isComplete( String $target = NULL, String $code = NULL ) {
		return parent::isComplete( $this->target, $this->code );
	}
	
	public function isNotComplete() {
		try {
			return !( $this->isComplete( $this->target, $this->code ) );
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}