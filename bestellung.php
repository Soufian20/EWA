<?php
    header ("Content-type: text/html");
    $title="Bestellung";
?>
<!DOCTYPE html>
<html lang="de">
<?php
    echo <<<EOT
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>$title</title>
    </head>
EOT;
?>

<body>
    <?php
        echo <<<EOT
        <!-- NAVIGATIONSLEISTE -->
         <nav class="header-nav"> 
            <a href="index.php"><h1>Pi<span>zz</span>a</h1></a>
            <ul>
                <li><a href="index.php">Startseite</a></li>
                <li class="active"><a href="bestellung.php">Bestellung</a></li>
                <li><a href="kunde.php">Kunde</a></li>
                <li><a href="pizzabaecker.php">Pizzabäcker</a></li>
                <li><a href="fahrer.php">Fahrer</a></li>
            </ul>
         </nav>
        <!-- SPEISEKARTE -->
        <section class="Speisekarte">
        <h2>Speisekarte</h2>
        <span class="gericht">1. Pizza Margherita € 4,00</span>
        <br>
        <img alt="Margherita" width="250" height="150" src="images/Pizza-Margherita.png"> <br>
        <span class="gericht">2. Pizza Salami € 4,50</span>
        <br>
        <img  alt="Salami" width="250" height="150" src="images/Pizza-Salami.png"> <br>
        <span class="gericht">3. Pizza Fungi € 5,50</span>
        <br>
        <img alt="Fungi" width="250" height="150" src="images/Pizza-Champignons.png"><br>
        </section>
        <br>
        <!-- WARENKORB -->
        <section class="Warenkorb">
            <h2>Warenkorb</h2>
            <form id="form1" action='test.php' method="POST" accept-charset="UTF-8"> <!--https://echo.fbi.h-da.de/-->
            <select tabindex="1" name="Bestellungen[]" size="3" multiple>
                <option selected="selected"> Margherita</option>
                <option>            Salami</option>
                <option>            Hawaii</option>
            </select>
            <br>
            <br>
        </section>
            <br> 
        <section class="Formular">
            <label id="Gesamtpreis" for="Gesamtpreis">Gesamtpreis:
                <output>14.50€</output>  
            </label>
            <fieldset>
                    <span>Bitte machen Sie Ihre Eingaben</span> <br>                       
                    <label>
                        <span>Vorname:</span> <br>
                        <input type="text" id="Vorname" name="Vorname" value="" placeholder="Ihr Vorname" maxlength="15" required/>    
                    </label>
                    <br>
                    <label>
                        <span>Nachname:</span> <br>
                        <input type="text" id="Nachname" name="Nachname" value="" placeholder="Ihr Nachname" maxlength="15" required/>
                    </label>
                    <br>
                    <label>
                        <span>Adresse:</span> <br>
                        <input type="text" id="Adresse" name="Adresse" value="" placeholder="Ihre Adresse" maxlength="15" required/> <!-- required: Feld darf nicht leer bleiben-->
                    </label>
                    <br>
                    <input form="form1" type="reset" value="Alles Löschen"/>
                    <input form="form1" type="reset" value="Auswahl Löschen"/>
                    <input type="submit" value="Bestellen"/>                        
            </fieldset>  
            </form>
        </section>
EOT;
        ?>
        <?php
            echo <<<EOT
            <section id="berechnung"> 
EOT;
        ?>
        <?php
                if(isset($_POST["submit"]))
                {
                    print_r($_POST);
                } 
        ?>
        <?php
            echo <<<EOT
            </section> 
EOT;
        ?>
        
    
</body>

</html>