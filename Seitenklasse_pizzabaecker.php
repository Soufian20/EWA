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

private function insert_option($indent, $name){
	echo ($indent."<option>".htmlspecialchars($name)."</option>\n");
}

protected function generateView() {
	//$pizzen = $this->getViewData();
	$this->generatePageHeader('Pizzabäcker');
	$bestellungen = $this->getViewData();
	echo <<<EOT
	<!-- Pizzabäcker -->
	<section class="bestellte-pizzen">
		<h2>Pizzabäcker (bestellte Pizzen)</h2>
EOT;
	for($i=0; $i < count($bestellungen); $i++)
	{
		if($bestellungen[$i]['Status'] == 'im Ofen' || $bestellungen[$i]['Status'] == 'Bestellung eingegangen')
		{
			echo '<div class=Baeckerstatus>';
			// Hole Pizzaname von der bestellten Pizza
			$sql = "SELECT PizzaName FROM Angebot WHERE Pizzanummer =".$bestellungen[$i]['fPizzaNummer'].";";
			$recordset = $this->database->query($sql);
			if (!$recordset)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
			$record = $recordset->fetch_assoc();
			echo ' BestellungID: '.$bestellungen[$i]['fBestellungID'].' <br>';
			echo ''.$record['PizzaName'].': '.$bestellungen[$i]['Status'].'<br><br>';
			echo <<<EOT
			<span>Im Ofen</span>
			<span>fertig</span>
			<br>
			<form id="fomr'.$i.'" action="Seitenklasse_pizzabaecker.php" method="POST" accept-charset="UTF-8">
				<fieldset id="form'.$i.'">
				<input type="radio" id="radio" name="radio-status" value="im Ofen"/>
				<input type="radio" id="radio" name="radio-status" value="fertig"/>
				<br>
				<button type="submit" value="$i" name="index_pizzanummer">Update</button>
				</fieldset>	
			</form>
			</div>
EOT;

		}
		echo '</section>';	
	}
    $this->generatePageFooter();
}

protected function processReceivedData() {
	parent::processReceivedData();
	if(isset($_POST['index_pizzanummer']))
    {    
		print_r($_POST);
		if(isset($_POST['radio-status']))
		{
			if($_POST['radio-status']=="im Ofen" || $_POST['radio-status']=="fertig")
			{
				$status= $_POST['radio-status'];
				$fbestellungid= $this->getViewData()[$_POST['index_pizzanummer']]['fBestellungID'];
				$fpizzanummer= $this->getViewData()[$_POST['index_pizzanummer']]['fPizzaNummer'];
				echo $fbestellungid;
				echo $fpizzanummer;

				$sql= "UPDATE `BestelltePizza` SET `Status`= '$status' WHERE fBestellungID = '$fbestellungid' AND fPizzaNummer ='$fpizzanummer'";
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