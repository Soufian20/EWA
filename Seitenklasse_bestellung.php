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


		// erstellt leeren Warenkorb
		echo <<<EOT
		<!-- WARENKORB -->
        <section class="Warenkorb">
            <h2>Warenkorb</h2>
            <form id="form1" action='test.php' method="POST" accept-charset="UTF-8"> <!--https://echo.fbi.h-da.de/-->
            <select tabindex="1" name="Bestellungen[]" size="3" multiple>
                <option selected="selected"> Margherita</option>
                <option>            Salami</option>
                <option>            Hawaii</option>
            </select>
            <br>
            <br>
        </section>
           
EOT;
    
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