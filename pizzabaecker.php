<?php
    header ("Content-type: text/html");
    $title="Pizzabäcker";
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
                <li><a href="kunde.php">Kunde</a></li>
                <li class="active"><a href="pizzabaecker.php">Pizzabäcker</a></li>
                <li><a href="fahrer.php">Fahrer</a></li>
            </ul>
         </nav>
         <!-- Pizzabäcker -->
         <section class="bestellte-pizzen">
             <h2>Pizzabäcker (bestellte Pizzen)</h2>
             <span>bestellt</span>
             <span>Im Ofen</span>
             <span>fertig</span>
             <fieldset>
                 <ul class="radioButtons">
                         <label for="Margherita">
                             Margherita
                             <input type="radio" id="Margherita" name="Margherita" />
                             <input type="radio" id="Margherita" name="Margherita" />
                             <input type="radio" id="Margherita" name="Margherita" />
                         </label> 
                         <br>
                         <label for="Salami">
                             Salami
                             <input type="radio" id="Salami" name="Salami" />
                             <input type="radio" id="Salami" name="Salami" />
                             <input type="radio" id="Salami" name="Salami" />
                         </label>
                         <br>
                         <label for="Hawaii">
                             Hawaii
                             <input type="radio" id="Hawaii" name="Hawaii" />
                             <input type="radio" id="Hawaii" name="Hawaii" />
                             <input type="radio" id="Hawaii" name="Hawaii" />
                         </label>
                 </ul>
             </fieldset>
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