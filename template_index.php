<?php
require_once './Page.php';

class Index extends Page
{
    protected function __construct() {
		parent::__construct();
	}

	protected function __destruct() {
		parent::__destruct();
    }
    
    protected function getViewData() {
       
	}



protected function generateView() {
    
    $this->generatePageHeader('Index');
    


    
    $this->generatePageFooter();
    }   
    


protected function processReceivedData() {
    parent::processReceivedData();
    
    
}

public static function main() {
    try {
        $page = new Index();
        $page->processReceivedData();
        $page->generateView();
    }
    catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}}

Index::main();
?>
