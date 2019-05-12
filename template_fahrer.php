<?php
require_once './Page.php';

class Fahrer extends Page
{
    protected function __construct() {
		parent::__construct();
	}

	protected function __destruct() {
		parent::__destruct();
    }
    
    protected function getViewData() {
        $sql = "SELECT * FROM bestelltePizza WHERE Status='Bereit zur Abholung' OR Status='in Zustellung';";
       

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

        $fertige_bestellungen = $recordset->fetch_all(MYSQLI_ASSOC);
        
/* 		while($row = $recordset->fetch_assoc()) {
            echo $row["PizzaNummer"]. "<br>";
            echo $row["PizzaName"]. "<br>";
            echo $row["Bilddatei"]. "<br>";
            echo $row["Preis"]. "<br>";
        } */
        
        $recordset->free();
        //print_r($fertige_bestellungen);
		return $fertige_bestellungen;
	}



protected function generateView() {
    $fertige_bestellungen = $this->getViewData();
    //echo $bestellungen[1]["PizzaName"];
    $numOfRecords = count($fertige_bestellungen);

    $this->generatePageHeader('Fahrer');
    ?>

    <?php  //print_r ($fertige_bestellungen); ?> 
    <section class="Lieferstatus">
        <h2>Fahrer (Lieferstatus)</h2>
        
        <?php for($i=0; $i < count($fertige_bestellungen); $i++){ ?>
            <div class="Status">
                <label>
                    <?php 
                        $sql = "SELECT PizzaName FROM Angebot WHERE Pizzanummer =".$fertige_bestellungen[$i]['fPizzaNummer'].";";
                        $recordset = $this->database->query ($sql);
                        $bestellungen1 = $recordset->fetch_all(MYSQLI_ASSOC);
                        echo $bestellungen1[0]['PizzaName']; 
                    ?> 
                    :<output><?php echo $fertige_bestellungen[$i]['Status'] ?></output>  
                </label> 
                <br>

                <?php echo '<form id="form'.$i.'" action="template_fahrer.php" method="POST" accept-charset="UTF-8">';?>
                
                <input type="radio" name="status" value="in Zustellung"> In Zustellung<br>
                <input type="radio" name="status" value="zugestellt"> Zugestellt<br>  
                <button type="submit" value="<?php echo $i;?>" name="index_pizzanummer">Status ändern</button>
                </form>
            </div>
        <?php } ?>
        
        
        
        </select>
        
        
        </section>

        
   
    <?php  
    $this->generatePageFooter();

    
    }   
    


protected function processReceivedData() {
    parent::processReceivedData();
   
    if(isset($_POST['status']))
    {    $status= $_POST['status'];
        //echo $status ;
        $fbestellungsid= $this->getViewData()[$_POST['index_pizzanummer']]['fBestellungsID'];
        $fpizzanummer= $this->getViewData()[$_POST['index_pizzanummer']]['fPizzaNummer'];
       // print_r($fpizzanummer) ;

        
        $sql= "UPDATE `BestelltePizza` SET `Status`= '$status' WHERE fBestellungsID = '$fbestellungsid' AND fPizzaNummer ='$fpizzanummer'";
        mysqli_query($this->database, $sql);
        //print_r($bestelltePizzen);

        
      
    
    }
        

        //$this->database->query($sql);
    //print_r($Adresse);
    } 
    


public static function main() {
    try {
        $page = new Fahrer();
        $page->processReceivedData();
        $page->generateView();
    }
    catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}}

Fahrer::main();
?>
