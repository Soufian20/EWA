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
    <title>Kundenseite</title>
    <!-- Skaliert Breite für mobile Geräte nach Bildschirmbreite  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

    <header>
            <h1> --- Header --- </h1>
    </header>

    <!-- NAVIGATIONSLEISTE -->
    <nav>  
        <h2> --- Nav --- </h2>
        <ul>
            <li><a href="Bestellung.php"> Bestellung</a></li>
            <li><a href="Kunde.php"> Kunde</a></li>
            <li><a href="Fahrer.php"> Fahrer</a></li>
            <li><a href="Pizzabaecker.php"> Pizzabäcker</a></li>
        </ul>
    </nav>

    <!-- KUNDENANSICHT -->
    <section class="main_kunde">
        <h2>Meine Bestellung</h2>
        
        <label for="Margherita">Margherita:
            <output id="Margherita" for="Margherita">bestellt</output>  
        </label> 
        <br>
        <label for="Salami">Salami:
            <output id="Salami" for="Salami">Im Ofen</output>  
        </label>
        <br>
        <button tabindex="1" accesskey="s" onclick="location.href='bestellung.php'" type="button">Neue Bestellung</button>
        
        
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