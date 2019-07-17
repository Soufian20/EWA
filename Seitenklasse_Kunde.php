<?php
require_once './SessionManagement.php';
$handler = new MySessionHandler();
session_set_save_handler($handler, true);
session_start();

require_once './Page.php';
class Kunde extends Page
{
    protected function __construct() {
		parent::__construct();
	}
	protected function __destruct() {
		parent::__destruct();
    }
    
    protected function getViewData() 
{
	$bestelltepizzen = NULL;
	// Hole die Pizzen von der Session	
    if(isset($_SESSION['BestellungID']))
    {
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
		echo '<section class="Lieferstatus"><h2>Bitte legen Sie eine Bestellung an</h2></section>';
		
	}
	else{
	echo <<<EOT
	<!-- Kundenansicht -->
	<script src="StatusUpdate.js"></script>
	<section class="Lieferstatus" id="Lieferstatus">
	<h2>Kunde (Lieferstatus)</h2>
EOT;

}
    
    $this->generatePageFooter();
}
protected function processReceivedData() {
	parent::processReceivedData();
} 	
public static function main() {
    try {
        $page = new Kunde();
        $page->processReceivedData();
        $page->generateView();
    }
    catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}
}
Kunde::main();
?>