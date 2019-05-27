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
		$sql = "SELECT * FROM bestellung";

		$recordset = $this->database->query ($sql);
		if (!$recordset)
			throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
		        
		// read selected records into result array
		$bestellungen = array();
		$record = $recordset->fetch_assoc();
		while ($record) {
			//$pizzen[] = $record["PizzaName"];
			$bestellungen[] = $record;
		    $record = $recordset->fetch_assoc();
		}
		$recordset->free();
		return $bestellungen;
	}

private function insert_option($indent, $name){
	echo ($indent."<option>".htmlspecialchars($name)."</option>\n");
}

protected function generateView() {
	$bestellungen = $this->getViewData();
	$this->generatePageHeader('Fahrer');
	echo <<<EOT
	<!-- Fahrer -->
	<h2 id="head_fahrer">Fahrer (auslieferbare Bestellungen)</h2>
EOT;
	
	 for($i=0; $i < count($bestellungen); $i++)
	{
		// Zähle wie viele Bestellungen der jeweilige Kunde bestellt hat
		$sql2 = "SELECT COUNT(fBestellungID) FROM bestelltepizza WHERE fBestellungID={$bestellungen[$i]['BestellungID']}";
		$recordset2 = $this->database->query($sql2);
			if (!$recordset2)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
		$record2 = $recordset2->fetch_assoc();
		
		// Checke ob alle Pizzen vom jeweiligen Kunden fertig oder unterwegs sind
		for ($j=0; $j < $record2['COUNT(fBestellungID)']; $j++) 
		{ 
			$sql3 = "SELECT COUNT(*) FROM bestelltepizza WHERE fBestellungID={$bestellungen[$i]['BestellungID']} AND (Status='fertig' OR Status='unterwegs')";
			$recordset3 = $this->database->query($sql3);
			if (!$recordset3)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
			$record3 = $recordset3->fetch_assoc();
			
			if($record2['COUNT(fBestellungID)'] == $record3['COUNT(*)'])
			{
				// Alle Pizzen sind vom jeweiligen Kunden fertig
				echo '<div class="Fahrerstatus">';
				echo '<span>Vorname: '. $bestellungen[$i]["Vorname"].'</span><br>';
				echo '<span>Nachname: '.$bestellungen[$i]["Nachname"].'</span><br>';
				echo '<span>Adresse: '.$bestellungen[$i]["Adresse"].'</span><br>';
				echo '<span>Bestellzeitpunkt: '.$bestellungen[$i]["Bestellzeitpunkt"].'</span><br>';
				echo <<<EOT
				<span>unterwegs</span>
				<span>geliefert</span>
				<br>
				<form id="fomr'.$i.'" action="Seitenklasse_fahrer.php" method="POST" accept-charset="UTF-8">
					<fieldset id="form'.$i.'">
					<input type="radio" id="radio" name="radio-status" value="unterwegs"/>
					<input type="radio" id="radio" name="radio-status" value="geliefert"/>
					<br>
					<button type="submit" value="$i" name="index_bestellnummer">Update</button>
					</fieldset>	
				</form>
				</div> 
				</section>
EOT;
				break;
			}
			else 
			{
				// Es sind nicht alle fertig also gehe weiter
				continue;
			}

			// für Bestellungen die unterwegs sind
			/* $sql4 = "SELECT COUNT(*) FROM bestelltepizza WHERE fBestellungID={$bestellungen[$i]['BestellungID']} AND Status='unterwegs'";
			$recordset4 = $this->database->query($sql3);
			if (!$recordset4)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
			$record4 = $recordset4->fetch_assoc();

			if($record2['COUNT(fBestellungID)'] == $record4['COUNT(*)'])
			{
				// Alle Pizzen sind vom jeweiligen Kunden unterwegs
				echo '<div class="Fahrerstatus">';
				echo '<span>Vorname: '. $bestellungen[$i]["Vorname"].'</span><br>';
				echo '<span>Nachname: '.$bestellungen[$i]["Nachname"].'</span><br>';
				echo '<span>Adresse: '.$bestellungen[$i]["Adresse"].'</span><br>';
				echo '<span>Bestellzeitpunkt: '.$bestellungen[$i]["Bestellzeitpunkt"].'</span><br>';
				echo <<<EOT
				<span>unterwegs</span>
				<span>geliefert</span>
				<br>
				<form id="fomr'.$i.'" action="Seitenklasse_fahrer.php" method="POST" accept-charset="UTF-8">
					<fieldset id="form'.$i.'">
					<input type="radio" id="radio" name="radio-status" value="unterwegs"/>
					<input type="radio" id="radio" name="radio-status" value="geliefert"/>
					<br>
					<button type="submit" value="$i" name="index_bestellnummer">Update</button>
					</fieldset>	
				</form>
				</div> 
				</section>
EOT;
				break;
			}
			else 
			{
				// Es sind nicht alle unterwegs also gehe weiter
				continue;
			} */
			
		}
		
	} 

	$this->generatePageFooter();

}

protected function processReceivedData() {
	parent::processReceivedData();
	{    
		if(isset($_POST['radio-status']))
		{
			if($_POST['radio-status']=="unterwegs" || $_POST['radio-status']=="geliefert")
			{
				$status= $_POST['radio-status'];
				$fbestellungid= $this->getViewData()[$_POST['index_bestellnummer']]['BestellungID'];
				print_r($fbestellungid);
				$sql= "UPDATE `BestelltePizza` SET `Status`= '$status' WHERE fBestellungID='$fbestellungid'";
				$recordset = $this->database->query($sql);
				if (!$recordset)
				{
					throw new Exception("UPDATE fehlgeschlagen: ".$this->database->error);
				} 
			}
		}
    }
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