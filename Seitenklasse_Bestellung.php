<?php

require_once './Page.php';
class Bestellung extends Page
{
    protected function __construct() 
    {
		parent::__construct();
	}
    protected function __destruct() 
    {
		parent::__destruct();
    }
    
    protected function getViewData() 
    {
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
    
    
protected function generateView() 
{
    $pizzen = $this->getViewData();
    $this->generatePageHeader('Bestellung');
    
    echo<<<EOT
    <!-- BESTELLUNGSANSICHT -->
    <section class="main_bestellung">
        <h2>Bestellung</h2>
        <section class="Speisekarte">
                <h2>Speisekarte</h2>
EOT;

    for ($i = 0; $i < count($pizzen); $i++)
    {
        $pizza = $pizzen[$i];
        $PizzaName = $pizza["PizzaName"];
        $PizzaPreis = $pizza["Preis"];
        $PizzaBild = $pizza["Bilddatei"];
    echo<<<EOT
                <figure id="Pizza{$i}" onclick="PushInWarenkorb('{$PizzaName}','{$PizzaPreis}')">
                    <img src="{$PizzaBild}" alt="{$PizzaName}" title="{$PizzaName}" width="100" height="100" />
                    <figcaption>{$PizzaName} kostet {$PizzaPreis}€</figcaption>
                </figure>
EOT;
    }

    echo<<<EOT
    <!-- WARENKORB -->
    <section class="Warenkorb" id="warenkorb">
        <h2>Warenkorb</h2>
        <h3>Sie haben <span id="AnzahlPizza">0</span>  Pizzen ausgewählt</h3>
        <span>Gesamtpreis <span id="Gesamtpreis">0€</span></span>  
    </section>
EOT;

    echo<<<EOT
    <section class="Formular" id=formular>
    <h2>Formular</h2>
    <form id="bestell_form" action="https://echo.fbi.h-da.de/" method="POST" accept-charset="UTF-8">
    <fieldset>
        <label for="Vorname">Vorname</label>  
        <input type="text" name="Vorname" id="Vorname" value="" placeholder="Ihr Vorname" maxlength="10" required>
        <br>
        <label for="Nachname">Nachname</label>  
        <input type="text" name="Nachname" id="Nachname" value="" placeholder="Ihr Nachname" maxlength="10" required>
        <br>
        <label for="Adresse">Adresse</label>  
        <input type="text" name="Adresse" id="Adresse" value="" placeholder="Ihre Adresse" maxlength="10" required>
        <br>

        <button type="submit" tabindex="1" accesskey="s">Eingaben absenden</button>
        <button type="reset" tabindex="2" accesskey="r">Eingaben zurücksetzen</button>
    </fieldset>
    </form>
    </section>

EOT;


    //von Speisekarte
    echo '</section>';
    // von mai_bestellung
    echo '</section>'; 
    $this->generatePageFooter();
}

protected function processReceivedData() 
{
	parent::processReceivedData();
	
	
} 	
public static function main() 
{
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