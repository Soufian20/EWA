
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
        $sql1 = "SELECT * FROM Bestellung;";
       

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

        // //print_r($fertige_bestellungen);
        // //echo 'Array-Size' . count($fertige_bestellungen);

        // $richtigFertig = Array();
        // $count =0;
        // $counter =0;
        // $prevBest= $fertige_bestellungen[0]['fBestellungsID'];
        // for ($i=0; $i < count($fertige_bestellungen); $i++) { 
    
        //    if($fertige_bestellungen[$i]['fBestellungsID']==$prevBest){
        //        $count++;
        //        //echo "Count: ". $count ;
        //                //echo "Count: ". $counter; 
        //    }
        //    else {
               
        //        $counter=0;
        //        //echo "Counter: ". $counter; 
        //        for ($a=0; $a < count($alle_bestellungen); $a++) { 
        //            if($alle_bestellungen[$a]['fBestellungsID'] == $prevBest){
        //                $counter++;
        //                //echo "Count: ". $count ;
        //                //echo "Count: ". $counter; 
        //            }
        //         }
                
        //             if($count==$counter) {
        //                 for ($b=0; $b < count($fertige_bestellungen) ; $b++) { 
        //                     if($fertige_bestellungen[$b]['fBestellungsID'] == $prevBest){

        //                      $richtigFertig[] = $fertige_bestellungen[$b];
                            
        //                     }
        //                 }
                        
                        
        //              }
        //        }

        //     if($fertige_bestellungen[$i]['fBestellungsID']!=$prevBest && $i==(count($fertige_bestellungen)-1)){
        //         $richtigFertig[] = $fertige_bestellungen[$i];
        //     }
        //     // if(count($fertige_bestellungen == $i+1))
        //     // {
        //     //     $counter = 0;
        //     //     //echo "Counter: ". $counter; 
        //     //     for ($a=0; $a < count($alle_bestellungen); $a++) { 
        //     //         if($alle_bestellungen[$a]['fBestellungsID'] == $prevBest){
        //     //             $counter++;
        //     //            // echo "Count: ". $count ;
        //     //            // echo "Count: ". $counter; 
        //     //         }
        //     //      }
                 
        //     //          if($count==$counter) {
        //     //              for ($b=0; $b < count($fertige_bestellungen) ; $b++) { 
        //     //                  if($fertige_bestellungen[$b]['fBestellungsID'] == $prevBest) $richtigFertig[] = $fertige_bestellungen[$b];
        //     //                  //print_r($fertige_bestellungen[$b]);
        //     //              }
        //     //              return $richtigFertig;  
        //     //           }
                
        //     // }
        //     $prevBest = $fertige_bestellungen[$i]['fBestellungsID'];

        // }
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
		return $alle_bestellungen;
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
		// Zähle wie viele Bestellungen der jeweilige Kunde bestellt hat
		$sql2 = "SELECT COUNT(fBestellungsID) FROM bestelltepizza WHERE fBestellungsID={$bestellungen[$i]['BestellungsID']}";
		$recordset2 = $this->database->query($sql2);
			if (!$recordset2)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
		$record2 = $recordset2->fetch_assoc();
		
		// Checke ob alle Pizzen vom jeweiligen Kunden fertig oder unterwegs sind
		for ($j=0; $j < $record2['COUNT(fBestellungsID)']; $j++) 
		{ 
			$sql3 = "SELECT COUNT(*) FROM bestelltepizza WHERE fBestellungsID={$bestellungen[$i]['BestellungsID']} AND (Status='bereit zur Abholung' OR Status='in Zustellung')";
			$recordset3 = $this->database->query($sql3);
			if (!$recordset3)
			{
				throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
			}
			$record3 = $recordset3->fetch_assoc();
			
			if($record2['COUNT(fBestellungsID)'] == $record3['COUNT(*)'])
			{
				// Alle Pizzen sind vom jeweiligen Kunden fertig
				echo '<div class="Fahrerstatus">';
				echo '<span>Vorname: '. htmlspecialchars($bestellungen[$i]["Vorname"]).'</span><br>';
				echo '<span>Nachname: '.htmlspecialchars($bestellungen[$i]["Nachname"]).'</span><br>';
				echo '<span>Adresse: '.htmlspecialchars($bestellungen[$i]["Adresse"]).'</span><br>';
				echo '<span>Bestellzeitpunkt: '.htmlspecialchars($bestellungen[$i]["Bestellzeitpunkt"]).'</span><br>';
				echo <<<EOT
				<span>unterwegs</span>
				<span>geliefert</span>
				<br>
				<form id="fomr'.$i.'" action="template_fahrer.php" method="POST" accept-charset="UTF-8">
					<fieldset id="form'.$i.'">
					<input type="radio" id="radio" name="radio-status" value="in Zustellung"/>
					<input type="radio" id="radio" name="radio-status" value="geliefert"/>
					<br>
					<button type="submit" value="$i" name="index_bestellnummer">Update</button>
					</fieldset>	
				</form>
				</div> 
				</section>
EOT;
				break;
			}
			else 
			{
				// Es sind nicht alle fertig also gehe weiter
				continue;
			}
    

    
    }   
}
$this->generatePageFooter();
}


protected function processReceivedData() {
	parent::processReceivedData();
	{    
		if(isset($_POST['radio-status']))
		{
			if($_POST['radio-status']=="in Zustellung" || $_POST['radio-status']=="geliefert")
			{
				$status= mysqli_real_escape_string($this->database, $_POST['radio-status']);
				$fbestellungsid= mysqli_real_escape_string($this->database, $this->getViewData()[$_POST['index_bestellnummer']]['BestellungsID']);
				//print_r($fbestellungsid);
				$sql= "UPDATE `BestelltePizza` SET `Status`= '$status' WHERE fBestellungsID='$fbestellungsid'";
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
