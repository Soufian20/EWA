<?php
    header ("Content-type: text/html");
    $title="Kunde";
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
                <li><a href="bestellung.php">Bestellung</a></li>
                <li class="active"><a href="kunde.php">Kunde</a></li>
                <li><a href="pizzabaecker.php">Pizzabäcker</a></li>
                <li><a href="fahrer.php">Fahrer</a></li>
            </ul>
         </nav>
        <!-- Kundenansicht -->
        <section class="Lieferstatus">
        <h2>Kunde (Lieferstatus)</h2>
        <label>Margherita:
            <output>bestellt</output>  
        </label> <br>
        <label>Salami:
            <output>Im Ofen</output>  
        </label> <br>
        <label>Tonno:
            <output>fertig</output>  
        </label> <br>
        <label>Hawai:
            <output>bestellt</output>  
        </label> <br><br>
        <button onclick="location.href='bestellung.php'" type="button">
            Neue Bestellung</button>
        </section>
        EOT;
        ?>    
</body>

</html>