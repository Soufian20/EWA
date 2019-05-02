<?php
require_once './Page.php';

class Bestellung extends Page
{
    protected function __construct() {
		parent::__construct();
	}

	protected function __destruct() {
		parent::__destruct();
    }
    
    protected function getViewData() {
		$sql = "SELECT * FROM Angebot";

		$recordset = $this->database->query ($sql);
		if (!$recordset)
			throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
		        
		// read selected records into result array
		$pizza = array();
		$record = $recordset->fetch_assoc();
		while ($record) {
		    $pizza[] = $record["Pizzaname"];
		    $record = $recordset->fetch_assoc();
		}
		$recordset->free();
		return $pizza;
	}



protected function generateView() {
    $pizza = $this->getViewData();
    $numOfRecords = count($pizza);

    $this->generatePageHeader('Bestellung');
    echo HI;
    $this->generatePageFooter();
}

protected function processReceivedData() {
    parent::processReceivedData();
}

public static function main() {
    try {
        $page = new Bestellung();
        $page->processReceivedData();
        $page->generateView();
    }
    catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}
}
Bestellung::main();

?>