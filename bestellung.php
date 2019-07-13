<?php

function generateView()
{
    echo <<<EOT
       
    <!DOCTYPE html>
    <html lang="de">  
    <head>
    <meta charset="UTF-8" />
    <!-- für später: CSS include -->
    <!-- <link rel="stylesheet" href="XXX.css"/> -->
    <!-- für später: JavaScript include -->
    <!-- <script src="XXX.js"></script> -->
    <title>Bestellseite</title>
    <!-- Skaliert Breite für mobile Geräte nach Bildschirmbreite  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

    <header>
            <h1> --- Header --- </h1>
    </header>

    <!-- NAVIGATIONSLEISTE -->
    <nav>  
        <h1> --- Nav --- </h1>
        <ul>
            <li><a href="Bestellung.php"> Bestellung</a></li>
            <li><a href="Kunde.php"> Kunde</a></li>
            <li><a href="Fahrer.php"> Fahrer</a></li>
            <li><a href="Pizzabaecker.php"> Pizzabäcker</a></li>
        </ul>
    </nav>

    <!-- BESTELLUNGSANSICHT -->
    <section class="main_bestellung">
        <h2>Bestellung</h2>
        <section class="Speisekarte">
                <h2>Speisekarte</h2>

                <figure>
                    <img src="images/Pizza-Margherita.png" alt="Pizza Margherita" title="Pizza Margherita" width="100" height="100" />
                    <figcaption>Pizza Margherita</figcaption>
                </figure>
                <span>4.00€</span> 

                <figure>
                    <img src="images/Pizza-Salami.png" alt="Pizza Salami" title="Pizza Salami" width="100" height="100" />
                    <figcaption>Pizza Salami</figcaption>    
                </figure>
                <span>4.00€</span> 
        </section>

        <section class="Warenkorb">
            <h2>Warenkorb</h2>

            <form id="bestell_form" action="https://echo.fbi.h-da.de/" method="POST" accept-charset="UTF-8">

                <select tabindex="1" name="Bestellungen[]" size="3" multiple>
                    <option selected="selected"> Margherita</option>
                    <option>                     Salami</option>
                </select>
                <br>
                <label id="Gesamtpreis" for="Gesamtpreis">Gesamtpreis:
                    <output>14.50€</output>  
                </label>

                <fieldset>
                    <label for="Vorname">Vorname</label>  
                    <input type="text" name="Vorname" id="Vorname" value="" placeholder="Ihr Vorname" maxlength="10" required>
                    <br>
                    <label for="Nachname">Nachname</label>  
                    <input type="text" name="Nachname" id="Nachname" value="" placeholder="Ihr Nachname" maxlength="10" required>
                    <br>
                    <label for="Adresse">Adresse</label>  
                    <input type="text" name="Adresse" id="Adresse" value="" placeholder="Ihre Adresse" maxlength="10" required>
                    <br>

                    <button type="submit" tabindex="1" accesskey="s">Eingaben absenden</button>
                    <button type="reset" tabindex="2" accesskey="r">Eingaben zurücksetzen</button>
                </fieldset>
            </form>

        </section>

            
    </section>

    <footer>
        <p>© 2019 by Soufian</p>
    </footer>
    </body>
    </html>

EOT;
}

?>

<?php

//Main
generateView();

?>