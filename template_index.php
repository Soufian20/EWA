<?php
session_start();
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
    ?>
    <div class = "Lieferstatus"><form action="template_index.php" method="post">
    <input type="submit" name="someAction" value="GO" />
    </form></div>


    <?php

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['someAction']))
{
    $_SESSION = array(); 
    echo('<div class= "Lieferstatus">Hat geklappt!</div>');  
}



    
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
