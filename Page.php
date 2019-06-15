<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class Page for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @license  http://www.h-da.de  none
 * @Release  1.2
 * @link     http://www.fbi.h-da.de
 */

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
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
        error_reporting(E_ALL);

        // open database
        $host = "localhost";
        $user = "root";
        $pwd = "";
        $this->database = new MySQLi($host, $user, $pwd, "pizzaservice");

        // check connection to database
        if (mysqli_connect_errno()) {
            throw new Exception("Keine Verbindung zur Datenbank: " . mysqli_connect_error());
        }

        // set character encoding to UTF-8
        if (!$this->database->set_charset("utf8")) {
            throw new Exception("Fehler beim Laden des Zeichensatzes UTF-8: " . $this->database->error);
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
     *
     * @param $headline $headline is the text to be used as title of the page
     *
     * @return none
     */
    protected function generatePageHeader($headline = "")
    {
        $headline = htmlspecialchars($headline);
        $kunde_active = '';
        $bestellung_active = '';
        $fahrer_active = '';
        $pizzabäcker_active = '';
        $index_active = '';
        if ($headline == 'Pizzabäcker') {
            $pizzabäcker_active = 'active';
        }
        if ($headline == 'Bestellung') {
            $bestellung_active = 'active';
        }
        if ($headline == 'Kunde') {
            $kunde_active = 'active';
        }
        if ($headline == 'Fahrer') {
            $fahrer_active = 'active';
        }
        if ($headline == 'Index') {
            $index_active = 'active';
        }
        header("Content-type: text/html; charset=UTF-8");

        // to do: output common beginning of HTML code
        // including the individual headline
        // output HTML header
        echo <<<EOT
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8"/>
EOT;
        if ($headline == 'Fahrer') {
            echo '<meta http-equiv="refresh" content="7; url=Seitenklasse_fahrer.php" />';
        }

        if ($headline == 'Pizzabäcker') {
            echo '<meta http-equiv="refresh" content="7; url=Seitenklasse_pizzabaecker.php" />';
        }

        echo <<<EOT
            <title>$headline</title>
            <link rel="stylesheet" type="text/css" href="styles.css">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">


        </head>
        <body>
        <header class="header">

        <div class="wrap">

            <h2 class="logo"><a href="template_index.php">Pi<span>zz</span>a</a></h2>

            <a id="menu-icon">&#9776; Menu</a>

            <nav class="navbar">
            <ul class="menu">
                <li><a class="$index_active" href="template_index.php">Home</a></li><!--
            --><li><a class="$bestellung_active" href="template_bestellung.1.php">Bestellung</a></li><!--
            --><li><a class="$kunde_active" href="Seitenklasse_kunde.php">Kunde</a></li><!--
            --><li><a  class="$pizzabäcker_active " href="Seitenklasse_pizzabaecker.php">Pizzabäcker</a></li><!--
            --><li><a  class="$fahrer_active" href="Seitenklasse_fahrer.php">Fahrer</a></li>
            </ul>
            </nav>

        </div>
        </header>





        <script  src="./script.js"></script>



EOT;
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter()
    {
        // to do: output common end of HTML code
        echo <<<EOT
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

// <header class="header">

//   <div class="wrap">

//     <h2 class="logo"><a href="#">Website Logo</a></h2>

//     <a id="menu-icon">&#9776; Menu</a>

//     <nav class="navbar">
//       <ul class="menu">
//         <li><a class="active" href="#">Home</a></li><!--
//       --><li><a href="#">About</a></li><!--
//       --><li><a href="#">Blog</a></li><!--
//       --><li><a href="#">Work</a></li><!--
//       --><li><a href="#">Contact</a></li>
//       </ul>
//     </nav>

//   </div>
// </header>

// <div class="content">

//   <h2>Simple Responsive Navigation Menu created by <a title="Follow me at twitter" href="https://twitter.com/mithicher">@mithicher</a></h2>
//   <p>Built with Sass &amp; Compass.</p>

// </div>
