
let warenkorb = new Array();

function PushInWarenkorb(PizzaName, Preis) 
{
    "use strict";
    console.log(PizzaName, "für" ,Preis ,"wurde in Warenkorb hinzugefügt");
    var pizza = { name: PizzaName, price: parseFloat(Preis)};
    warenkorb.push(pizza);
    console.log(warenkorb);
    WarenkorbAusgeben();
}

function WarenkorbAusgeben()
{
    "use strict";
    let gesamtpreis = 0;

    //Zeigt an wie viele Pizzen im Warenkorb sind
    document.getElementById('AnzahlPizza').firstChild.nodeValue = warenkorb.length;

    // Liste zum ausgaben des Warenkorbs erstellen
    let list_element = document.createElement('ul');
    list_element.id = "liste";

    // Prüfe und Lösche, wenn Liste schon gefüllt ist
    if (document.getElementById("liste")) 
    {
        document.getElementById('warenkorb').removeChild(document.getElementById("liste"));
    }

    for (let i = 0; i < warenkorb.length; i++)
    {
        // Berechnet Gesamtpreis
        gesamtpreis += warenkorb[i].price;

        // Erstelle List-items aus den Pizzen im Warenkorb
        let list_item = document.createElement('li');
        list_item.id = "list_item_nr" + i;
        let span_element = document.createElement('span');
        span_element.classList.add('pizza_warenkorb');
        let pizzaname = document.createTextNode(warenkorb[i].name);
        span_element.appendChild(pizzaname);
        list_item.appendChild(span_element);

        // Erstelle Button um List-item zu löschen
        let input_element = document.createElement('input');
        input_element.setAttribute('type', 'button');
        input_element.setAttribute('class', 'myButton');
        input_element.setAttribute('value', 'X');
        input_element.setAttribute('id', 'loeschen');
        input_element.setAttribute('onclick', 'LoescheListItem(' + i + ');');

        // Fügt es List-item hinzu
        span_element.appendChild(input_element);
        list_item.appendChild(span_element);

        // Füge List-item in Liste hinzu
        list_element.appendChild(list_item);
    }

    // Zeigt Liste an
    document.getElementById('warenkorb').appendChild(list_element);
    // Zeigt Gesamtpreis an
    document.getElementById('Gesamtpreis').firstChild.nodeValue = gesamtpreis.toFixed(2) + '€';
    console.log("Gesamtpreis -> " + gesamtpreis);
}

// List-item aus dem Warenkorb entfernen
function LoescheListItem(id)
{
    "use strict";
    warenkorb.splice(id, 1);
    console.log("Listitem " + id + " wurde entfernt")
    WarenkorbAusgeben();
}
