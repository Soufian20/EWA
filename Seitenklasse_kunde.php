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
		return $bestelltepizzen;
	}

protected function generateView() {
	$bestellungen = $this->getViewData();
	$this->generatePageHeader('Kunde');
	echo <<<EOT
	<!-- Kundenansicht -->
	<section class="Lieferstatus">
EOT;
	$prexBestellungID = ''; // Bestellung davor
	for($i=0; $i < count($bestellungen); $i++)
	{	
		$currentBestellungID = $bestellungen[$i]['fBestellungID'];
		if ($currentBestellungID != $prexBestellungID)
		{
			echo '<div class="Bestellstatus">';
			echo '<h2>Kunde (Lieferstatus)</h2>';
			$prexBestellungID = $currentBestellungID;
			echo '<span>Vorname: '. htmlspecialchars($bestellungen[$i]["Vorname"]) .'</span><br>';
			echo '<span>Nachname: '. htmlspecialchars($bestellungen[$i]["Nachname"]) .'</span><br>';
			echo '<span>Adresse: '. htmlspecialchars($bestellungen[$i]["Adresse"]) .'</span><br>';
			echo '<span>Bestellzeitpunkt: '. htmlspecialchars($bestellungen[$i]["Bestellzeitpunkt"]) .'</span><br>';

			for($j=0; $j < count($bestellungen); $j++)
			{
				if($currentBestellungID == $bestellungen[$j]['fBestellungID'])
				{
					echo '<label>'. htmlspecialchars($bestellungen[$j]['PizzaName']) .':
					<output>'. htmlspecialchars($bestellungen[$j]['Status']) .'</output> 
					</label> <br>';		
				}
			}
		}			
		echo '</div>';			
	} 
	echo '</section>';	
    
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