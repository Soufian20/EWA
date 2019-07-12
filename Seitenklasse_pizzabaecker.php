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
		$sql = "SELECT fBestellungID, PizzaName, fPizzaNummer, PizzaID, Status FROM bestelltepizza
		INNER JOIN angebot ON bestelltepizza.fPizzaNummer = angebot.PizzaNummer
		ORDER BY fBestellungID";

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
	
		<section class="Lieferstatus">
		<h2>Pizzabäcker (Lieferstatus)</h2>
		
EOT;
	
	
	for($i=0; $i < count($bestellungen); $i++)
	{	
		$checked = '';
		if($bestellungen[$i]['Status'] == 'im Ofen') $checked = ' checked';
		if($bestellungen[$i]['Status'] == 'im Ofen' || $bestellungen[$i]['Status'] == 'Bestellung eingegangen')
		{
			
			echo '<div class="Status">';
			echo ' BestellungID: '. htmlspecialchars($bestellungen[$i]['fBestellungID']) .' <br>';
			echo ''. htmlspecialchars($bestellungen[$i]['PizzaName']) .': '. htmlspecialchars($bestellungen[$i]['Status']) .'<br><br>';
			echo <<<EOT
			<span class="span-radio">Im Ofen</span>
			<span class="span-radio">fertig</span>
			<br>
			<form id="fomr'.$i.'" action="Seitenklasse_pizzabaecker.php" method="POST" accept-charset="UTF-8">
				<fieldset id="form'.$i.'">
				<input type="radio" class="radio" name="radio-status" value="im Ofen" $checked/>
				<input type="radio" class="radio" name="radio-status" value="fertig"/>
				<br>
				<button type="submit" value="$i" name="index_pizzanummer">Update</button>
				</fieldset>	
			</form>
			</div>
EOT;

		}
			
	}
	echo '</section>';
    $this->generatePageFooter();
}

protected function processReceivedData() {
	parent::processReceivedData();
    {    
		if(isset($_POST['radio-status']))
		{
			if($_POST['radio-status']=="im Ofen" || $_POST['radio-status']=="fertig")
			{
				$status= $_POST['radio-status'];
				print_r($_POST);
				$fbestellungid = mysqli_real_escape_string($this->database, $this->getViewData()[$_POST['index_pizzanummer']]['fBestellungID']);
				echo ($fbestellungid);
				$pizzaID = mysqli_real_escape_string($this->database, $this->getViewData()[$_POST['index_pizzanummer']]['PizzaID']);
				echo ($pizzaID);

				$sql= "UPDATE `BestelltePizza` SET `Status`= '$status' WHERE fBestellungID = '$fbestellungid' AND PizzaID = '$pizzaID'";
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