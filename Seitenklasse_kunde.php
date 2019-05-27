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
		$sql = "SELECT * FROM bestelltepizza";

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
		

		// Hole Kundendaten
		$sql2 = "SELECT * FROM Bestellung WHERE BestellungID={$bestellungen[$i]['fBestellungID']}";
		$recordset2 = $this->database->query($sql2);
			if (!$recordset2)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
		$record2 = $recordset2->fetch_assoc();
		

		$currentBestellungID = $bestellungen[$i]['fBestellungID'];
		if ($currentBestellungID != $prexBestellungID)
		{
			echo '<div class="Bestellstatus">';
			echo '<h2>Kunde (Lieferstatus)</h2>';
			$prexBestellungID = $currentBestellungID;
			echo '<span>Vorname: '. $record2["Vorname"].'</span><br>';
			echo '<span>Nachname: '.$record2["Nachname"].'</span><br>';
			echo '<span>Adresse: '.$record2["Adresse"].'</span><br>';
			echo '<span>Bestellzeitpunkt: '.$record2["Bestellzeitpunkt"].'</span><br>';

			for($j=0; $j < count($bestellungen); $j++)
			{
				if($currentBestellungID == $bestellungen[$j]['fBestellungID'])
				{
					// Hole PizzaName
					$sql = "SELECT PizzaName FROM Angebot WHERE PizzaNummer=".$bestellungen[$j]['fPizzaNummer'].";";
					$recordset = $this->database->query($sql);
						if (!$recordset)
						{
							throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
						}
					$record = $recordset->fetch_assoc();
					echo '<label>'.$record['PizzaName'].':
					<output>'.$bestellungen[$j]['Status'].'</output>  
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