<?php
require_once './SessionManagement.php';
$handler = new MySessionHandler();
session_set_save_handler($handler, true);
session_start();

// Liefert Serverseitig die Daten für die Kundenseite
// Liefert nur die Statusdaten der bestellten Pizzen im JSON-Format
require_once './Page.php';
class S_KundenStatus extends Page
{
    protected function __construct() {
		parent::__construct();
	}
	protected function __destruct() {
		parent::__destruct();
    }
    
    protected function getViewData() {
    if(isset($_SESSION['BestellungID']))
    {    
        $sql = "SELECT * FROM bestelltepizza 
                INNER JOIN bestellung ON bestelltepizza.fBestellungID = bestellung.BestellungID
                INNER JOIN Angebot ON bestelltepizza.fPizzaNummer = angebot.PizzaNummer
                WHERE fBestellungID={$_SESSION['BestellungID']}";
        $recordset = $this->database->query($sql);
        if (!$recordset)
        {
            throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
        }     
        // read selected records into result array
        $bestelltepizzen = $recordset->fetch_all(MYSQLI_ASSOC);
        
        $recordset->free();
    }
    return $bestelltepizzen;
}
protected function generateView() {
    // serialisierten JSON-String erzeugen enthält die bestellten Pizzen
    $bestelltepizzen = $this->getViewData();
    $serializedData = json_encode($bestelltepizzen);

    //serialisierten JSON-String als Antwort
	echo $serializedData;
}
protected function processReceivedData() {
    parent::processReceivedData();
    // Nachrichtenheader auf JSON setzen
	header("Content-Type: application/json; charset=UTF-8");
} 	
public static function main() {
    try {
        $page = new S_KundenStatus();
        $page->processReceivedData();
        $page->generateView();
    }
    catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}
}
S_KundenStatus::main();
?>