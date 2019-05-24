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
		$sql = "SELECT * FROM bestelltePizza WHERE Status='fertig'";

		$recordset = $this->database->query ($sql);
		if (!$recordset)
			throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
		        
		// read selected records into result array
		$pizzen = array();
		$record = $recordset->fetch_assoc();
		while ($record) {
			//$pizzen[] = $record["PizzaName"];
			$pizzen[] = $record;
		    $record = $recordset->fetch_assoc();
		}
		$recordset->free();
		return $pizzen;
	}

private function insert_option($indent, $name){
	echo ($indent."<option>".htmlspecialchars($name)."</option>\n");
}

protected function generateView() {
	$fertigepizzen = $this->getViewData();
	$this->generatePageHeader('Fahrer');
	echo <<<EOT
	<!-- Fahreransicht -->
	<section class="Lieferstatus">
EOT;
	$prexBestellungID = ''; // Bestellung davor
	for($i=0; $i < count($fertigepizzen); $i++)
	{
		

		// Hole Kundendaten
		$sql2 = "SELECT * FROM Bestellung WHERE BestellungID={$fertigepizzen[$i]['fBestellungID']}";
		$recordset2 = $this->database->query($sql2);
			if (!$recordset2)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
		$record2 = $recordset2->fetch_assoc();
		

		$currentBestellungID = $fertigepizzen[$i]['fBestellungID'];
		if ($currentBestellungID != $prexBestellungID)
		{
			echo '<div class="Bestellstatus">';
			echo '<h2>Kunde (Lieferstatus)</h2>';
			$prexBestellungID = $currentBestellungID;
			echo '<span>Vorname: '. $record2["Vorname"].'</span><br>';
			echo '<span>Nachname: '.$record2["Nachname"].'</span><br>';
			echo '<span>Adresse: '.$record2["Adresse"].'</span><br>';
			echo '<span>Bestellzeitpunkt: '.$record2["Bestellzeitpunkt"].'</span><br>';

			for($j=0; $j < count($fertigepizzen); $j++)
			{
				if($currentBestellungID == $fertigepizzen[$j]['fBestellungID'])
				{
					// Hole PizzaName
					$sql = "SELECT PizzaName FROM Angebot WHERE PizzaNummer=".$fertigepizzen[$j]['fPizzaNummer'].";";
					$recordset = $this->database->query($sql);
						if (!$recordset)
						{
							throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
						}
					$record = $recordset->fetch_assoc();
					echo '<label>'.$record['PizzaName'].':
					<output>'.$fertigepizzen[$j]['Status'].'</output>  
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