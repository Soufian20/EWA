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
	$bestelltepizzen = NULL;	
	if(isset($_SESSION['BestellungID'])){
	$sql = "SELECT * FROM bestelltepizza 
			INNER JOIN bestellung ON bestelltepizza.fBestellungID = bestellung.BestellungID
			INNER JOIN Angebot ON bestelltepizza.fPizzaNummer = angebot.PizzaNummer
			WHERE fBestellungID={$_SESSION['BestellungID']}";

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
	}

		return $bestelltepizzen;
	}

protected function generateView() {
	
	$bestellungen = $this->getViewData();
	$this->generatePageHeader('Kunde');
	if(!isset($bestellungen[0]['fBestellungID']))
	{
		echo '<div class="Lieferstatus"><h1>Bitte Bestellung für Kunden anlegen</h1></section>';
		
	}
	else{
	echo <<<EOT
	<!-- Kundenansicht -->
	<script src="StatusUpdate.js"></script>
	<section class="Lieferstatus" id="test123">
	</div>
EOT;
	 
	
}
    
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