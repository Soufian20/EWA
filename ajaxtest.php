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

    }

    protected function generateView()
    {

        $this->getViewData();
        $this->processReceivedData();

    }

    protected function processReceivedData()
    {
        //parent::processReceivedData();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bestellung = json_decode(file_get_contents("php://input"), true);
            //header("Content-type: text/plain; charset=UTF-8");
            // print_r($bestellung);
            //$bestelltePizzen = $_POST['Bestellungen'];
            // print_r($bestellung);

            
            
            $Vorname = mysqli_real_escape_string($this->database, $bestellung['Vorname']);
            $Nachname = mysqli_real_escape_string($this->database, $bestellung['Nachname']);
            $Adresse = mysqli_real_escape_string($this->database, $bestellung['Adresse']);
            $created_date = date("Y-m-d H:i:s");
            echo $created_date;
            $sql = "INSERT INTO `Bestellung` (`BestellungID`, `Adresse`, `Vorname`, `Nachname`, `Bestellzeitpunkt`) VALUES (NULL, '$Adresse', '$Vorname','$Nachname','$created_date');";
            mysqli_query($this->database, $sql);
            print_r($bestellung);
            for ($i = 0; $i < Count($bestellung['Pizzen']); $i++) {
                $pizzaname = $bestellung['Pizzen'][$i]['name'];
                echo ($pizzaname);
                $sql = "SELECT BestellungID FROM Bestellung WHERE Bestellzeitpunkt='$created_date' union SELECT PizzaNummer FROM Angebot WHERE PizzaName='$pizzaname';";
                $recordset = $this->database->query($sql);
                $pizza = $recordset->fetch_all(MYSQLI_ASSOC);
                print_r($pizza);
                $fbestnummer = $pizza[0]['BestellungID'];

                $fpizzanummer = $pizza[1]['BestellungID'];
                $sqli = "INSERT INTO `BestelltePizza` (`PizzaID`, `fBestellungID`, `fPizzaNummer`, `Status`) VALUES (NULL, '$fbestnummer', '$fpizzanummer', 'Bestellung eingegangen');";
                mysqli_query($this->database, $sqli);

            }
            $sql = "SELECT BestellungID FROM Bestellung WHERE BestellungID=(SELECT max(BestellungID) FROM Bestellung);";
            $recordset = $this->database->query($sql);
            $pizza23 = $recordset->fetch_all(MYSQLI_ASSOC);
            $_SESSION['BestellungID'] = $pizza23[0]['BestellungID'];
            echo ('Die Sessionvariable laute:' . $_SESSION['BestellungID']);
        }

    }

    public static function main()
    {
        try {
            $page = new PageTemplate();
            //$page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

PageTemplate::main();
