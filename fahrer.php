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
                <li ><a href="bestellung.php">Bestellung</a></li>
                <li><a href="kunde.php">Kunde</a></li>
                <li><a href="pizzabaecker.html">Pizzabäcker</a></li>
                <li class="active"><a href="fahrer.php">Fahrer</a></li>
            </ul>
         </nav>
        <!-- SPEISEKARTE -->
        <section class="Speisekarte fahrer">
        <h2>Lieferaufträge</h2>
        </section>
        <br><br>
        <section class="Speisekarte fahrer">
        <br>
        <span class="gericht">1. Peter Einstein<br>Schillerstraße 12<br>543576 Mannheim</span>
        <br>
        <br>
        
        <select>
            <option value="im Ofen">im Ofen</option>
            <option value="bereit zur Abholung">bereit zur Abholung</option>
            <option value="ausgeliefert">ausgeliefert</option>
        </select>
        <br>
        <br>
        </section>
        <br>
        <section class="Speisekarte fahrer">
        <br>
        <span class="gericht">2. Heribert Bruchhagen<br>Hanoverscher Weg 96<br>45466 Hanover</span>
        <br>
        <br>
        
        <select>
        
            <option value="im Ofen">im Ofen</option>
            <option value="bereit zur Abholung" selected>bereit zur Abholung</option>
            <option value="ausgeliefert">ausgeliefert</option>
        </select>
        <br>
        <br>
        </section>
        
        EOT;
      
        ?>
        
    
</body>

</html>