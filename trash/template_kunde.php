
<?php
session_start();
require_once './Page.php';

class Kunde extends Page
{
    protected function __construct() {
		parent::__construct();
	}

	protected function __destruct() {
		parent::__destruct();
    }
    
    protected function getViewData() {
        if(isset($_SESSION['BestellungsID'])){
        $sql = "SELECT * FROM bestelltePizza WHERE fBestellungsID = ".$_SESSION['BestellungsID'].";";
    }
       else $sql = "SELECT * FROM bestelltePizza;";

        $recordset = $this->database->query ($sql);
        $numRows = mysqli_num_rows($recordset);
		if (!$recordset)
			throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
		        
		// read selected records into result array
		
	/* 	for ($count = 0; $count < $numRows; $count++){
            $row = mysqli_fetch_array($recordset, MYSQLI_ASSOC);
            //echo json_encode($row);   
            //print_r($row) ; 
        } */

        $bestellungen = $recordset->fetch_all(MYSQLI_ASSOC);
        
/* 		while($row = $recordset->fetch_assoc()) {
            echo $row["PizzaNummer"]. "<br>";
            echo $row["PizzaName"]. "<br>";
            echo $row["Bilddatei"]. "<br>";
            echo $row["Preis"]. "<br>";
        } */
        
        $recordset->free();
       
		return $bestellungen;
	}



protected function generateView() {
    $bestellungen = $this->getViewData();
    //echo $bestellungen[1]["PizzaName"];
    $numOfRecords = count($bestellungen);

    $this->generatePageHeader('Kunde');
    $prevBestellungID = '';
    ?>
<?php  ?>
  
    <section class="Lieferstatus">
        <h2>Kunde (Lieferstatus)</h2>
        
        <?php for($i=0; $i <count($bestellungen); $i++){ ?>
        
        <?php 
        $tmpB = htmlspecialchars($bestellungen[$i]['fBestellungsID']); 
        if($tmpB != $prevBestellungID) { ?>
            <div class="Status">
                <?php 
                $prevBestellungID = $tmpB;?>
                Bestellnummer : <?php echo $tmpB; ?>
                    <br> <?php
                for( $a=0; $a <count($bestellungen); $a++){
                    if($tmpB == $bestellungen[$a]['fBestellungsID']){ ?>
                    
                    <label>
                    <?php 
                        $sql = "SELECT PizzaName FROM Angebot WHERE PizzaNummer =".$bestellungen[$a]['fPizzaNummer'].";";
                        $recordset = $this->database->query ($sql);
                        $bestellungen1 = $recordset->fetch_all(MYSQLI_ASSOC);
                        echo htmlspecialchars($bestellungen1[0]['PizzaName']); 
                    ?> 
                    :<output>
                        <?php 
                            echo htmlspecialchars($bestellungen[$a]['Status']);  
                        ?>
                    </output>  
                </label> 
                <br>
                   <?php  }
                } 
                $prevBestellungID = $tmpB;
            }
                ?>    
            </div>
             <?php   }        
        ?>    
        </section>
    <?php  
    $this->generatePageFooter();
}  
    


protected function processReceivedData() {
    parent::processReceivedData();
  
    } 
    


public static function main() {
    try {
        $page = new Kunde();
        $page->processReceivedData();
        $page->generateView();
    }
    catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}}

Kunde::main();
?>
