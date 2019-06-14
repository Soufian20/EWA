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
        $sql = "SELECT * FROM BestelltePizza WHERE Status='bereit zur Abholung' OR Status='in Zustellung';";
        $sql1 = "SELECT * FROM BestelltePizza;";
       

        $recordset = $this->database->query ($sql);
        $recordset1 = $this->database->query ($sql1);
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
        $alle_bestellungen = $recordset1->fetch_all(MYSQLI_ASSOC);

        //print_r($fertige_bestellungen);
        //echo 'Array-Size' . count($fertige_bestellungen);

        $richtigFertig = Array();
        $count =0;
        $counter =0;
        $prevBest= $fertige_bestellungen[0]['fBestellungsID'];
        for ($i=0; $i < count($fertige_bestellungen); $i++) { 
    
           if($fertige_bestellungen[$i]['fBestellungsID']==$prevBest){
               $count++;
               //echo "Count: ". $count ;
                       //echo "Count: ". $counter; 
           }
           else {
               
               $counter=0;
               //echo "Counter: ". $counter; 
               for ($a=0; $a < count($alle_bestellungen); $a++) { 
                   if($alle_bestellungen[$a]['fBestellungsID'] == $prevBest){
                       $counter++;
                       //echo "Count: ". $count ;
                       //echo "Count: ". $counter; 
                   }
                }
                
                    if($count==$counter) {
                        for ($b=0; $b < count($fertige_bestellungen) ; $b++) { 
                            if($fertige_bestellungen[$b]['fBestellungsID'] == $prevBest){

                             $richtigFertig[] = $fertige_bestellungen[$b];
                            
                            }
                        }
                        
                        
                     }
               }

            if($fertige_bestellungen[$i]['fBestellungsID']!=$prevBest && $i==(count($fertige_bestellungen)-1)){
                $richtigFertig[] = $fertige_bestellungen[$i];
            }
            // if(count($fertige_bestellungen == $i+1))
            // {
            //     $counter = 0;
            //     //echo "Counter: ". $counter; 
            //     for ($a=0; $a < count($alle_bestellungen); $a++) { 
            //         if($alle_bestellungen[$a]['fBestellungsID'] == $prevBest){
            //             $counter++;
            //            // echo "Count: ". $count ;
            //            // echo "Count: ". $counter; 
            //         }
            //      }
                 
            //          if($count==$counter) {
            //              for ($b=0; $b < count($fertige_bestellungen) ; $b++) { 
            //                  if($fertige_bestellungen[$b]['fBestellungsID'] == $prevBest) $richtigFertig[] = $fertige_bestellungen[$b];
            //                  //print_r($fertige_bestellungen[$b]);
            //              }
            //              return $richtigFertig;  
            //           }
                
            // }
            $prevBest = $fertige_bestellungen[$i]['fBestellungsID'];

        }
        // $adresse=array()
        // $adresse[0][$fertige_bestellungen1[$i]];
        // $adresse[0][nachname]  = "Pratzner";
/* 		while($row = $recordset->fetch_assoc()) {
            echo $row["PizzaNummer"]. "<br>";
            echo $row["PizzaName"]. "<br>";
            echo $row["Bilddatei"]. "<br>";
            echo $row["Preis"]. "<br>";
        } */
        // print_r($fertige_bestellungen1);
        // Array ( [0] => Array ( [BestellungsID] => 141 [Adresse] => Plankstadt [Bestellzeitpunkt] => 2019-05-26 11:10:46 [Vorname] => Lukas [Nachname] => Veith ) [1] => Array ( [BestellungsID] => 142 [Adresse] => Wunderland [Bestellzeitpunkt] => 2019-05-26 11:16:46 [Vorname] => Peter [Nachname] => Pan ) )
        $recordset->free();
        //print_r($richtigFertig);
		return $fertige_bestellungen;
	}



protected function generateView() {
    $fertige_bestellungen = $this->getViewData();
    //echo $bestellungen[1]["PizzaName"];
    $numOfRecords = count($fertige_bestellungen);
    $prevBestellungID = '';
    $this->generatePageHeader('Fahrer');
    ?>

    <?php  //print_r ($fertige_bestellungen); ?> 
    <section class="Lieferstatus">
        <h2>Fahrer (Lieferstatus)</h2>
        
        <?php for($i=0; $i < count($fertige_bestellungen); $i++){ 
            $tmpB = $fertige_bestellungen[$i]['fBestellungsID']; 
                if($tmpB != $prevBestellungID) { 
                ?>
            <div class="Status">
            <?php 
                $prevBestellungID = $tmpB;?>
                <span id = "Bestellnummer"> Bestellnummer: <?php echo $tmpB; ?></span>
                    <br> <br>  <?php
                for( $a=0; $a <count($fertige_bestellungen); $a++){
                    if($tmpB == $fertige_bestellungen[$a]['fBestellungsID']){ ?>
                <div id = "pizza_name">
                    <?php 
                        $sql = "SELECT PizzaName FROM Angebot WHERE Pizzanummer =".$fertige_bestellungen[$a]['fPizzaNummer'].";";
                        $recordset = $this->database->query ($sql);
                        $bestellungen1 = $recordset->fetch_all(MYSQLI_ASSOC);
                        echo $bestellungen1[0]['PizzaName']; 
                    ?> 
                      
                </div> 
                <?php  ?>
                <div><?php echo 'Aktueller Status: ' .' '. $fertige_bestellungen[$a]['Status'] ?></div>
                <br>

                <?php echo '<form id="form'.$i.'" action="template_fahrer.php" method="POST" accept-charset="UTF-8">';?>
                
                <input type="radio" name="status" value="in Zustellung"> In Zustellung<br>
                <input type="radio" name="status" value="zugestellt">Zugestellt<br>  <br>  
                <button type="submit" value="<?php echo $i;?>" name="index_pizzanummer">Status ändern</button>
                <br>  <br> 
                </form>
                <?php  }
                $prevBestellungID = $tmpB; 
                } ?>
            </div>
            <?php } 
         }   ?>
        
        
        
        </select>
        
        
        </section>

        
   
    <?php  
    $this->generatePageFooter();

    
    }   
    


protected function processReceivedData() {
    parent::processReceivedData();
   
    if(isset($_POST['status']))
    {    $status= mysqli_real_escape_string($this->database, $_POST['status']);
        //echo $status ;
        $fbestellungsid= mysqli_real_escape_string($this->database, $this->getViewData()[$_POST['index_pizzanummer']]['fBestellungsID']);
        $fpizzanummer= mysqli_real_escape_string($this->database, $this->getViewData()[$_POST['index_pizzanummer']]['fPizzaNummer']);
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