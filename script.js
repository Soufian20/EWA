
// Verwende Strict Mode 
"use strict";

var warenkorb = [];

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('menu-icon').addEventListener('click', function () {
        var element = document.getElementsByClassName("navbar");
        element[0].classList.toggle("expand");
        return false;
    });
});


function zumWarenkorb(nr) {
    var pizza = { name: document.getElementById('pizza' + nr).textContent, price: parseFloat(document.getElementById('price' + nr).dataset.price) };
    warenkorb.push(pizza);



    warenkorbAusgeben();





}

// Warenkorb als Liste in HTML einfügen 
function warenkorbAusgeben() {
    var gesamtpreis = 0;
    document.getElementById('anzahlpizza').firstChild.nodeValue = '' + warenkorb.length;

    var ulistwarenkorb = document.createElement('ul');
    ulistwarenkorb.id = 'liste';

    // Prüfen ob Warenkorb schon gefüllt, wenn ja dann lösche Warenkorb um aktuellen Warenkorb hinzufügen zu können
    if (document.getElementById("liste")) {
        document.getElementById('warenkorb').removeChild(document.getElementById("liste"));
    }


    for (var i = 0; i < warenkorb.length; ++i) {

        gesamtpreis += warenkorb[i].price;



        var lielement = document.createElement('li');
        lielement.id = "nr" + i;
        var spanelement = document.createElement('span');
        spanelement.classList.add('pizza123');
        var pizzaname = document.createTextNode(warenkorb[i].name);
        spanelement.appendChild(pizzaname);
        var inputelement = document.createElement('input');
        inputelement.setAttribute('type', 'button');
        inputelement.setAttribute('class', 'myButton');
        inputelement.setAttribute('value', 'X');
        inputelement.setAttribute('id', 'loeschen');
        inputelement.setAttribute('onclick', 'loescheNr(' + i + ');');
        spanelement.appendChild(inputelement);
        lielement.appendChild(spanelement);
        ulistwarenkorb.appendChild(lielement);


        // ausgabe += '<li id="nr' + i + '">';
        // ausgabe += '<span class = "pizza123">' + warenkorb[i].name ;
        // ausgabe += '<input type="button" class= "myButton" value="X" id="loeschen" onclick="loescheNr(' + i + ');" />'+'</span>';
        // ausgabe += '</li>';
    }

    document.getElementById('warenkorb').appendChild(ulistwarenkorb);
    document.getElementById('Gesamtpreis').firstChild.nodeValue = '' + gesamtpreis.toFixed(2);

    console.dir(warenkorb);
    console.log(gesamtpreis);

}
// Element aus dem Warenkorb entfernen 

function loescheNr(id) {
    warenkorb.splice(id, 1);
    warenkorbAusgeben();
}
// Kontaktdaten leeren, wenn Button "Warenkorb leeren" geklickt
function deleteContact() {
    document.getElementById('Vorname').value = '';
    document.getElementById('Nachname').value = '';
    document.getElementById('Adresse').value = '';
}
// Warenkorb leeren, wenn Button "Warenkorb leeren" geklickt

function deleteCart() {
    warenkorb = [];
    warenkorbAusgeben();

}



var xhr = new XMLHttpRequest();

function pushToDB() {


    // Prüfen ob Adresse eingegeben wurde
    if (document.getElementById('Adresse').value == '') {
        alert('Bitte Adresse eingeben!')
    }
    // Prüfen ob Vorname eingegeben wurde
    else if (document.getElementById('Vorname').value == '') {
        alert('Bitte Vorname eingeben!')
    }
    // Prüfen ob Nachname eingegeben wurde
    else if (document.getElementById('Nachname').value == '') {
        alert('Bitte Nachname eingeben!')
    }
    // Prüfen ob Pizzen ausgewählt wurde
    else if (warenkorb.length == 0) {
        alert('Bitte Pizzen auswählen!')
    }
    else {

        // Erstelle Object von Bestellung mit Kontaktdaten und Warenkorbinhalt
        var bestellung = {
            Adresse: document.getElementById('Adresse').value,
            Vorname: document.getElementById('Vorname').value,
            Nachname: document.getElementById('Nachname').value,
            Pizzen: warenkorb
        }




        xhr.open('POST', 'AJAX_bestellung.php', true);
        xhr.setRequestHeader('Content-type', 'application/json;=UTF-8');


        console.log(JSON.stringify(bestellung));
        xhr.send(JSON.stringify(bestellung));

        xhr.onload = function () {
            console.log(this.responseText);
        }

        alert('Vielen Dank für ihre Bestellung ---> Sie werden nun zur Übersicht weitergeleitet');
        deleteContact();
        deleteCart();
        window.open("Seitenklasse_kunde.php");

    }

}



