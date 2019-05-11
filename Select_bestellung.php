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
	<!-- NAVIGATIONSLEISTE -->
         <nav class="header-nav"> 
            <a href="index.php"><h1>Pi<span>zz</span>a</h1></a>
            <ul>
                <li><a href="index.php">Startseite</a></li>
                <li class="active"><a href="Select_bestellung.php">Bestellung</a></li>
                <li><a href="kunde.php">Kunde</a></li>
                <li><a href="pizzabaecker.php">Pizzabäcker</a></li>
                <li><a href="fahrer.php">Fahrer</a></li>
            </ul>
		</nav>
		<!-- SPEISEKARTE -->
        <section class="Speisekarte">
		<h2>Speisekarte</h2>
EOT;
		foreach($pizzen as $pizza)
		{
			//$this->insert_option("\t\t", $pizza);
			echo "<span class='gericht'>{$pizza["PizzaNummer"]} {$pizza["PizzaName"]} {$pizza["Preis"]}</span> <br>";
       		echo "<img alt='{$pizza["PizzaName"]}' width='250' height='150' src='{$pizza["Bilddatei"]}'> <br>";
		}
		
    
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