// EcmaScript welche die gelieferten Daten auswertet und über das DOM in die Kundenseite einfügt.
function process(json_input) 
{
    "use strict";
    let my_json = json_input;
    console.log("my_json -> " + my_json);
    //Packt JSON-String in ein Javascript-Object
    let obj = JSON.parse(my_json);
    console.log("Aus JSON erstelltes assoziatives Array -> " + obj);

    // 3 Hilfsfunktionen
    function span(string) 
    {
        let span = document.createElement("span");
        let vorname = document.createTextNode(string);
        span.appendChild(vorname);
        return span;
    }

    function spanblock(string) 
    {
        let span = document.createElement("span");
        let span_text = document.createTextNode(string);
        span.appendChild(span_text);
        return span;
    }

    function pizzadiv(index) 
    {
        let pizzadiv = document.createElement("div");
        let span1 = span(obj[index].PizzaName);
        let span2 = span(obj[index].Status);
        let span3 = span("->");
        pizzadiv.appendChild(span1);
        pizzadiv.appendChild(span3);
        pizzadiv.appendChild(span2);
        return pizzadiv;
    }

    let element_lieferstatus = document.getElementById("Lieferstatus");
    // Lösche alten Bestellstatus
    if (document.getElementById("bestellstatus"))
    {
        element_lieferstatus.removeChild(document.getElementById("bestellstatus"));
    }

    // Erstelle neuen Bestellstatus
    let div_bestellstatus = document.createElement("div");
    div_bestellstatus.id = "bestellstatus";
    var h3 = document.createElement("h3");
    var h3_text = document.createTextNode("Pizzen");
    h3.appendChild(h3_text);

    // Verbinde Bestellstatus mit Formulardaten
    div_bestellstatus.appendChild(h3);
    div_bestellstatus.appendChild(spanblock("BestellungID: " + obj[0].fBestellungID));
    div_bestellstatus.appendChild(spanblock("Vorname: " + obj[0].Vorname));
    div_bestellstatus.appendChild(spanblock("Nachname: " + obj[0].Nachname));
    div_bestellstatus.appendChild(spanblock("Adresse: " + obj[0].Adresse));
    div_bestellstatus.appendChild(spanblock("Bestellzeitpunkt: " + obj[0].Bestellzeitpunkt));

    // Um alle Pizzen anzuzeigen
    for (let index = 0; index < obj.length; index++) 
    {
        div_bestellstatus.appendChild(pizzadiv(index));
    }
    element_lieferstatus.appendChild(div_bestellstatus);
}

// Logik für AJAX-Aufruf
// request als globale Variable anlegen (haesslich, aber bequem)
var request = new XMLHttpRequest();

// Polling soll erst starten, nachdem Kundenseite vom Browser geladen und geparst wurde
window.onload = function start()
{
    // Legt und schickt AJAX-Request ab 
    requestData();
    // zyklisches Anfrage (Polling) des aktuellen Zustands einer Bestellung
    window.setInterval(requestData, 2000);
}

// fordert die Daten asynchron an
function requestData() 
{ 
    request.open("GET", "Serverseitig_KundenStatus.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null); // Request abschicken
};

// Filtert erfolgreiche Antwort raus und Ruft dann process auf um die Daten zu verarbeiten
function processData() 
{
  if (request.readyState == 4) 
  {
    // Uebertragung = DONE
    if (request.status == 200) 
    {
      // HTTP-Status = OK
      if (request.responseText != null) process(request.responseText);
      // Daten verarbeiten
      else console.error("Dokument ist leer");
    } else console.error("Uebertragung fehlgeschlagen");
  } else; // Uebertragung laeuft noch
}