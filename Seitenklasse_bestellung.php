<?php
session_start();
require_once './Page.php';

class Bestellung extends Page
{
    protected function __construct()
    {
        parent::__construct();
    }

    protected function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {

        $sql = "SELECT * FROM Angebot";


        $recordset = $this->database->query($sql);
        if (!$recordset)
            throw new Exception("Abfrage fehlgeschlagen: " . $this->database->error);

        // read selected records into result array


        $pizza = $recordset->fetch_all(MYSQLI_ASSOC);
        $recordset->free();
        return $pizza;
    }



    protected function generateView()
    {
        $pizza = $this->getViewData();
        $this->generatePageHeader('Bestellung');
        ?>



    <!-- SPEISEKARTE -->
    <section class="Speisekarte">
        <h2>Speisekarte</h2>


        <?php
        for ($i = 0; $i < count($pizza); $i++) {
            // echo $pizza[$i]["PizzaName"];
            //{$pizza[$i]["PizzaName"]}
            ?>
            <div style="cursor: pointer;" onclick="zumWarenkorb(<?php echo ($i + 1); ?>)">
                <span class="gericht, block">
                    <?php echo $pizza[$i]["PizzaNummer"] ?>.
                    <span id="pizza<?php echo ($i + 1); ?>"><?php echo $pizza[$i]["PizzaName"] ?></span>
                    €
                    <span id="price<?php echo ($i + 1); ?>" data-price="<?php echo $pizza[$i]["Preis"]  ?>">
                        <?php echo $pizza[$i]["Preis"]  ?>
                    </span>
                </span>
                <img alt="<?php echo $pizza[$i]["PizzaName"] ?>" width="250" height="150" src="<?php echo $pizza[$i]["Bilddatei"] ?>">
            </div>

        <?php } ?>




    </section>

    
    <!-- WARENKORB -->
    <section class="Warenkorb" id="warenkorb">
        <h2>Warenkorb</h2>
        <h3 id="pizzaanzahl">Sie haben <span id="anzahlpizza">0</span>  Pizzen ausgewählt</h3>
       
    </section>

    
    <section class="Formular">
        <h2>Details</h2>
        <label for="Gesamtpreis">Gesamtpreis:
            <output id="Gesamtpreis">0.00€</output>
        </label>
        <fieldset>
            <span>Bitte machen Sie Ihre Eingaben</span> <br>
            <label>
                <span>Vorname:</span> <br>
                <input type="text" id="Vorname" name="Vorname" value="" placeholder="Ihr Vorname" required />
            </label>
            <br>
            <label>
                <span>Nachname:</span> <br>
                <input type="text" id="Nachname" name="Nachname" value="" placeholder="Ihr Nachname" required />
            </label>
            <br>
            <label>
                <span>Adresse:</span> <br>
                <input type="text" id="Adresse" name="Adresse" value="" placeholder="Ihre Adresse" required /> <!-- required: Feld darf nicht leer bleiben-->
            </label>
            <br>
            <button id="deleteContact" onclick="deleteContact()">Kontaktdaten zurücksetzen</button>
            <button id="deleteCart" onclick="deleteCart()">Warenkorb leeren</button>
            <button id="button123" onclick="pushToDB()">Absenden</button>

        </fieldset>
        
    </section>






    <?php
    $this->generatePageFooter();
}



protected function processReceivedData()
{
    parent::processReceivedData();
    
}

public static function main()
{
    try {
        $page = new Bestellung();
        $page->generateView();
    } catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}
}

Bestellung::main();
?>