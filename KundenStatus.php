<?php	
session_start();
require_once './Page.php';


class PageTemplate extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    /**
     * Instantiates members (to be defined above).   
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */
    protected function __construct() 
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }
    
    /**
     * Cleans up what ever is needed.   
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        if(isset($_SESSION['BestellungsID'])){
            $sql = "SELECT * FROM bestelltePizza WHERE fBestellungsID = ".$_SESSION['BestellungsID'].";";
       
    
            $recordset = $this->database->query ($sql);
        
            if (!$recordset)
                throw new Exception("Abfrage fehlgeschlagen: ".$this->database->error);
                    
         
    
            $bestellungen = $recordset->fetch_all(MYSQLI_ASSOC);
            
    /* 		while($row = $recordset->fetch_assoc()) {
                echo $row["PizzaNummer"]. "<br>";
                echo $row["PizzaName"]. "<br>";
                echo $row["Bilddatei"]. "<br>";
                echo $row["Preis"]. "<br>";
            } */
            
            $recordset->free(); 
        }  
        return $bestellungen;
    }
    
 
     
    protected function generateView() 
    {   
        
        // $this->getViewData();
        // $this->generatePageHeader();
        // $this->generatePageFooter();
    }
    
    
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        $array = $this->getViewData();
        
       
        header("Content-Type: application/json; charset=UTF-8");
        $serializedData = json_encode($array);
        echo $serializedData;
      


    
    
    
 
}

  
    public static function main() 
    {
        try {
            $page = new PageTemplate();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


PageTemplate::main();

