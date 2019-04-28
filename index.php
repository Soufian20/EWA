<?php
    header ("Content-type: text/html");
    $title="Startseite";
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
                <li class="active"><a href="index.php">Startseite</a></li>
                <li><a href="bestellung.php">Bestellung</a></li>
                <li><a href="kunde.php">Kunde</a></li>
                <li><a href="pizzabaecker.html">Pizzabäcker</a></li>
                <li><a href="fahrer.php">Fahrer</a></li>
            </ul>
         </nav>
        <!-- Startseite -->
        EOT;
        ?>    
</body>

</html>