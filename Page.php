<?php	
 
 
/**
 * This abstract class is a common base class for all 
 * HTML-pages to be created. 
 * It manages access to the database and provides operations 
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.

 */ 
abstract class Page
{
    // --- ATTRIBUTES ---
    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;
    
    // --- OPERATIONS ---
    
    /**
     * Connects to DB and stores 
     * the connection in member $_database.  
     * Needs name of DB, user, password.
     *
     * @return none
     */
    protected function __construct() 
    {
        // activate full error checking
        error_reporting (E_ALL);
        // open database
        $host="localhost";
        $user="root";
        $pwd="";
        $this->database = new MySQLi($host, $user, $pwd, "pizzaservice");
        // check connection to database
        if (mysqli_connect_errno())
        {
            throw new Exception("Keine Verbindung zur Datenbank: ".mysqli_connect_error());   
        }
        // set character encoding to UTF-8
		if (!$this->database->set_charset("utf8"))
        {
            throw new Exception("Fehler beim Laden des Zeichensatzes UTF-8: ".$this->database->error);
        }
    }
    
    /**
     * Closes the DB connection and cleans up
     *
     * @return none
     */
    protected function __destruct()    
    {
        // to do: close database
        $this->database->close();
    }
    
    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     */
    protected function generatePageHeader($headline = "") 
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
    
		echo <<<EOT
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8"/>
            <!-- für später: JavaScript include -->
            <script src="script.js"></script>
            <title>$headline</title>
        </head>
        <body>
        <header>
            <h1> --- Header --- </h1>
        </header>
        <!-- NAVIGATIONSLEISTE -->
        <nav>  
        <h2> --- Nav --- </h2>
        <ul>
            <li><a href="Seitenklasse_Bestellung.php"> Bestellung</a></li>
            <li><a href="Seitenklasse_Kunde.php"> Kunde</a></li>
            <li><a href="Seitenklasse_Fahrer.php"> Fahrer</a></li>
            <li><a href="Seitenklasse_Pizzabaecker.php"> Pizzabäcker</a></li>
        </ul>
        </nav>
EOT;
    }

    protected function generatePageFooter() 
    {
        // to do: output common end of HTML code
        echo <<<EOT
        <footer>
        <p>© 2019 by Soufian</p>
        </footer>
        </body>
        </html>
EOT;
    }
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return none
     */
    protected function processReceivedData() 
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} // end of class
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >