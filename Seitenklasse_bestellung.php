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
		$sql = "SELECT * FROM Angebot";

		$recordset = $this->database->query ($sql);
		if (!$recordset)
		{
			throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
		}
		        
		// read selected records into result array
		$pizzen = array();
		$record = $recordset->fetch_assoc();
		while ($record) 
		{
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
	$pizzen = $this->getViewData();
	$this->generatePageHeader('Bestellung');

    $numOfRecords = count($pizzen);
	echo <<<EOT
		<!-- SPEISEKARTE -->
        <section class="Speisekarte">
		<h2>Speisekarte</h2>
EOT;

		// erstellt die Speisekarte von der Datenbank
		foreach($pizzen as $pizza)
		{
			//$this->insert_option("\t\t", $pizza);
			echo "<span class='gericht'>{$pizza["PizzaNummer"]} {$pizza["PizzaName"]}</span> <br>";
			   echo "<img alt='{$pizza["PizzaName"]}' width='250' height='150' src='{$pizza["Bilddatei"]}'> <br>";
			   echo "<button class='gericht-button'>{$pizza["Preis"]} €</button> <br><br>";
		}
		echo "</section> <br>";


		// erstellt Warenkorb
		echo <<<EOT
		<!-- WARENKORB -->
        <section class="Warenkorb">
            <h2>Warenkorb</h2>
            <form id="form1" action='Seitenklasse_bestellung.php' method="POST" accept-charset="UTF-8"> <!--https://echo.fbi.h-da.de/-->
			<select tabindex="1" name="Bestellungen[]" size="3" multiple>
EOT;
				foreach($pizzen as $pizza)
				{
                	echo '<option>'.$pizza["PizzaName"].'</option>';
				}
			echo<<<EOT
            </select>
            <br>
            <br>
		</section>  
		<br>  
EOT;
		echo <<<EOT
    <section class="Formular">
        <label id="Gesamtpreis" for="Gesamtpreis">Gesamtpreis:
            <output>00.00€</output>  
        </label>
        <fieldset>
                <span>Bitte machen Sie Ihre Eingaben</span> <br>                       
                <label>
                    <span>Vorname:</span> <br>
                    <input type="text" id="Vorname" name="Vorname" value="" placeholder="Ihr Vorname" maxlength="15" required/>    
                </label>
                <br>
                <label>
                    <span>Nachname:</span> <br>
                    <input type="text" id="Nachname" name="Nachname" value="" placeholder="Ihr Nachname" maxlength="15" required/>
                </label>
                <br>
                <label>
                    <span>Adresse:</span> <br>
                    <input type="text" id="Adresse" name="Adresse" value="" placeholder="Ihre Adresse" maxlength="15" required/> <!-- required: Feld darf nicht leer bleiben-->
                </label>
                <br>
                <input form="form1" type="reset" value="Alles Löschen"/>
                <input form="form1" type="reset" value="Auswahl Löschen"/>
                <input type="submit" name ="bestellen_btn" value="Bestellen"/>                        
        </fieldset>  
        </form>
	</section>
	<section id="berechnung"> 
    </section> 
EOT;
    
    $this->generatePageFooter();
}

protected function processReceivedData() {
	parent::processReceivedData();
	
	if(isset($_POST["bestellen_btn"]))
    {
        $bestelltePizzen = $_POST['Bestellungen']; // Name vom Warenkorb-Array

		// Setzte Variablen vom Formular und erstelle Datenbank Eintrag für eine Bestellung
        $Vorname = mysqli_real_escape_string($this->database, $_POST['Vorname']); 
        $Nachname = mysqli_real_escape_string($this->database, $_POST['Nachname']); 
        $Adresse = mysqli_real_escape_string($this->database, $_POST['Adresse']);
        $created_date = date("Y-m-d H:i:s");
        $sql = "INSERT INTO `Bestellung` (`BestellungID`, `Adresse`, `Bestellzeitpunkt`, `Vorname`, `Nachname`) VALUES (NULL, '$Adresse', '$created_date','$Vorname','$Nachname');";
		$recordset = $this->database->query ($sql);
		if (!$recordset)
		{
			throw new Exception("INSERT fehlgeschlagen: ".$this->database->error);
		}

		/*for($bestelltePizzen as $pizza) 
		{
        	$sql = "SELECT BestellungsID FROM Bestellung WHERE Bestellzeitpunkt='$created_date' union SELECT PizzaNummer FROM Angebot WHERE PizzaName='$pizza';";
			$recordset = $this->database->query($sql);
			if (!$recordset)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
		
			$pizza = $recordset->fetch_all(MYSQLI_ASSOC);
        	$fbestnummer = $pizza[0]['BestellungsID'];
        	$fpizzanummer = $pizza[1]['BestellungsID'];
        	$sqli = "INSERT INTO `BestelltePizza` (`PizzaID`, `fBestellungsID`, `fPizzaNummer`, `Status`) VALUES (NULL, '$fbestnummer', '$fpizzanummer', 'Bestellung eingegangen');";
        	mysqli_query($this->database, $sqli); 
		}*/
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