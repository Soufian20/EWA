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
		$sql = "SELECT b.BestellungID, b.Vorname, b.Nachname, b.Adresse, Bestellzeitpunkt, a.PizzaName, be.Status,SUM(a.Preis) AS Gesamtpreis
				FROM 
					bestellung b, angebot a, bestelltepizza be
				WHERE b.BestellungID = be.fBestellungID
				AND be.fPizzaNummer = a.PizzaNummer
				AND (be.Status = 'fertig' OR be.Status = 'unterwegs')
				GROUP BY BestellungID";

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
		
				// Alle Pizzen sind vom jeweiligen Kunden fertig
				echo '<div class="Fahrerstatus">';
				echo '<span>Vorname: '. htmlspecialchars($bestellungen[$i]["Vorname"]) .'</span><br>';
				echo '<span>Nachname: '. htmlspecialchars($bestellungen[$i]["Nachname"]) .'</span><br>';
				echo '<span>Adresse: '. htmlspecialchars($bestellungen[$i]["Adresse"]) .'</span><br>';
				echo '<span>Bestellzeitpunkt: '. htmlspecialchars($bestellungen[$i]["Bestellzeitpunkt"]).'</span><br>';
				echo '<span>Gesamtpreis: '. htmlspecialchars($bestellungen[$i]["Gesamtpreis"]).'</span><br>';
				echo '<span>Status: '. htmlspecialchars($bestellungen[$i]["Status"]).'</span><br>';
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
				$status = $_POST['radio-status'];
				$fbestellungid = mysqli_real_escape_string($this->database,$this->getViewData()[$_POST['index_bestellnummer']]['BestellungID']);
				$sql = "UPDATE `BestelltePizza` SET `Status`= '$status' WHERE fBestellungID='$fbestellungid'";
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