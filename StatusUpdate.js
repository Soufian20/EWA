
"use strict";

function process(json1) {
  var json = json1;
  var elementLieferstatus = document.getElementById("test123");
  if (document.getElementById("bestellstatus"))
    elementLieferstatus.removeChild(document.getElementById("bestellstatus"));
  var obj = JSON.parse(json);

  function spanblock(string) {
    var span = document.createElement("span");
    span.classList.add("block");
    var vorname = document.createTextNode(string);
    span.appendChild(vorname);
    return span;
  }
  function span(string) {
    var span = document.createElement("span");
    var vorname = document.createTextNode(string);
    span.appendChild(vorname);
    return span;
  }

  function pizzadiv(index) {
    var pizzadiv = document.createElement("div");
    var span1 = span(obj[index].PizzaName);
    var span2 = span(obj[index].Status);
    var span3 = span(":");
    pizzadiv.appendChild(span1);
    pizzadiv.appendChild(span3);
    pizzadiv.appendChild(span2);
    return pizzadiv;
  }

  var divBestellstatus = document.createElement("div");
  divBestellstatus.id = "bestellstatus";
  divBestellstatus.classList.add("Bestellstatus");
  var h2 = document.createElement("h2");
  var h2Text = document.createTextNode("Kunde (Lieferstatus)");
  var h3 = document.createElement("h3");
  var h3Text = document.createTextNode("Pizzen");

  h2.appendChild(h2Text);
  h3.appendChild(h3Text);
  divBestellstatus.appendChild(h2);

  
  divBestellstatus
    .appendChild(spanblock("Vorname: " + obj[0].Vorname));
    divBestellstatus
    .appendChild(spanblock("Nachname: " + obj[0].Nachname));
    divBestellstatus
    .appendChild(spanblock("Adresse: " + obj[0].Adresse));
    divBestellstatus
    .appendChild(spanblock("Bestellzeitpunkt: " + obj[0].Bestellzeitpunkt));
    divBestellstatus.appendChild(h3);
  for (let index = 0; index < obj.length; index++) {
    divBestellstatus.appendChild(pizzadiv(index));
  }
  document.getElementById("test123").appendChild(divBestellstatus);
  console.log(obj);
}

// request als globale Variable anlegen (haesslich, aber bequem)
var request = new XMLHttpRequest();

window.onload = function start(){
   
    requestData();
  window.setInterval (requestData, 2000);
}

function requestData() {
    // fordert die Daten asynchron an
    request.open("GET", "KundenStatus.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null); // Request abschicken
  };

function processData() {
  if (request.readyState == 4) {
    // Uebertragung = DONE
    if (request.status == 200) {
      // HTTP-Status = OK
      if (request.responseText != null) process(request.responseText);
      // Daten verarbeiten
      else console.error("Dokument ist leer");
    } else console.error("Uebertragung fehlgeschlagen");
  } else; // Uebertragung laeuft noch
}
