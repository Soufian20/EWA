<?php
session_start();
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
	$sql = "SELECT * FROM bestellung 
	INNER JOIN bestelltepizza ON bestellung.BestellungID = bestelltepizza.fBestellungID 
	WHERE BestellungID ={$_SESSION['BestellungID']}";

		$recordset = $this->database->query ($sql);
		if (!$recordset)
		{
			throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
		}     
		// read selected records into result array
		$bestelltepizzen = array();
		$record = $recordset->fetch_assoc();
		while ($record) 
		{
			$bestelltepizzen[] = $record;
		    $record = $recordset->fetch_assoc();
		}
		$recordset->free();

		return $bestelltepizzen;
	}

protected function generateView() {
	$serializedData = json_encode($this->getViewData());
	echo $serializedData;
}

protected function processReceivedData() {
	parent::processReceivedData();
	header("Content-Type: application/json; charset=UTF-8");
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