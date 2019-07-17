<?php
require_once './SessionManagement.php';
$handler = new MySessionHandler();
session_set_save_handler($handler, true);
session_start();
require_once './Page.php';
class C_KundenStatus extends Page
{
    protected function generateView()
    {
        $this->processReceivedData();
    }
    protected function processReceivedData()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Hole Bestellungdaten-JSON das mit POST übertragen wurde und kodiere in PHP-Variable
            $bestellung = json_decode(file_get_contents("php://input"), true);
            
            // mysqli maskiert Strings um daraus SQL-Statements zu machen
            $Vorname = mysqli_real_escape_string($this->database, $bestellung['Vorname']);
            $Nachname = mysqli_real_escape_string($this->database, $bestellung['Nachname']);
            $Adresse = mysqli_real_escape_string($this->database, $bestellung['Adresse']);
            $created_date = date("Y-m-d H:i:s");

            // Füge Bestellung in Datenbank hinzu
            $sql = "INSERT INTO `Bestellung` (`BestellungID`, `Adresse`, `Vorname`, `Nachname`, `Bestellzeitpunkt`) VALUES (NULL, '$Adresse', '$Vorname','$Nachname','$created_date');";
            mysqli_query($this->database, $sql);

            // Füge die bestellten Pizzen in der Datenbank hinzu
            for ($i = 0; $i < Count($bestellung['Pizzen']); $i++) 
            {
                $pizzaname = $bestellung['Pizzen'][$i]['name'];
                $sql = "SELECT b.BestellungID, a.PizzaNummer FROM Bestellung b, Angebot a 
                                                             WHERE b.Bestellzeitpunkt='$created_date' 
                                                             AND a.PizzaName='$pizzaname';"; 
                $recordset = $this->database->query($sql);
                $pizza = $recordset->fetch_all(MYSQLI_ASSOC);
                $fbestnummer = $pizza[0]['BestellungID'];
                $fpizzanummer = $pizza[0]['PizzaNummer'];

                $sql2 = "INSERT INTO `BestelltePizza` (`PizzaID`, `fBestellungID`, `fPizzaNummer`, `Status`) VALUES (NULL, '$fbestnummer', '$fpizzanummer', 'Bestellung eingegangen');";
                mysqli_query($this->database, $sql2);
            }

            // Hole die neuste BestellungsID und setzte es als Sessionvariable
            $sql = "SELECT BestellungID FROM Bestellung 
                                        WHERE BestellungID=(SELECT max(BestellungID) FROM Bestellung);";
            $recordset = $this->database->query($sql);
            $BestellungID = $recordset->fetch_all(MYSQLI_ASSOC);
            $_SESSION['BestellungID'] = $BestellungID[0]['BestellungID'];
            echo ("Die Sessionvariable -> " + $_SESSION['BestellungID']);
        }
    }
    public static function main()
    {
        try {
            $page = new C_KundenStatus();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}
C_KundenStatus::main();