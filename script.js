
"use strict";

var warenkorb = [];

// $(document).ready(function(){
//     $('#menu-icon').on('click', function(){
//       $('.navbar').toggleClass('expand');
//       return false;
//     });
//   });


  document.addEventListener("DOMContentLoaded", function(){
    document.getElementById('menu-icon').addEventListener('click', function(){
        var element = document.getElementsByClassName("navbar");
        element[0].classList.toggle("expand");
        return false;
      });
  });


function zumWarenkorb(nr) {
    var pizza = {name: document.getElementById('pizza' + nr).innerHTML, price : parseFloat(document.getElementById('price' + nr).dataset.price)};
    warenkorb.push(pizza);
    
    //console.log(gesamtpreis);
    warenkorbAusgeben();
     //console.dir(warenkorb);
    


// var elm = document.getElementById('price1')
// var first = elm.dataset.price
// var second = elm.dataset.somethingElse // camel case for multi-word

// console.log(first)
}

// Warenkorb als Liste in HTML einfügen 
function warenkorbAusgeben() {
    var ausgabe = '';
    var gesamtpreis =0;
    //document.getElementById("anzahlpizza").removeChild(document.getElementById("anzahlpizza"));
    document.getElementById('anzahlpizza').firstChild.nodeValue = ''+warenkorb.length;

    var ulistwarenkorb = document.createElement('ul');
    ulistwarenkorb.id = 'liste';
    if (document.getElementById("liste"))
    document.getElementById('warenkorb').removeChild(document.getElementById("liste"));
     warenkorb.sort();
        for (var i = 0; i < warenkorb.length; ++i) {
            
            gesamtpreis+=warenkorb[i].price;
            

            
            var lielement = document.createElement('li');
            lielement.id="nr"+i;
            var spanelement = document.createElement('span');
            spanelement.classList.add('pizza123');
            var pizzaname = document.createTextNode(warenkorb[i].name);
            spanelement.appendChild(pizzaname);
            var inputelement = document.createElement('input');
            inputelement.setAttribute('type', 'button');
            inputelement.setAttribute('class', 'myButton');
            inputelement.setAttribute('value', 'X');
            inputelement.setAttribute('id', 'loeschen');
            inputelement.setAttribute('onclick', 'loescheNr('+i+');');
            spanelement.appendChild(inputelement);
            lielement.appendChild(spanelement);
            ulistwarenkorb.appendChild(lielement);




            // ausgabe += '<li id="nr' + i + '">';
            // ausgabe += '<span class = "pizza123">' + warenkorb[i].name ;
            // ausgabe += '<input type="button" class= "myButton" value="X" id="loeschen" onclick="loescheNr(' + i + ');" />'+'</span>';
            // ausgabe += '</li>';
    }
    
    document.getElementById('warenkorb').appendChild(ulistwarenkorb);
    document.getElementById('Gesamtpreis').firstChild.nodeValue = ''+gesamtpreis.toFixed(2);

   
    
  
    // for (var i = 0; i < 10; i++) {
    //   var newLI = document.createElement('li');
    //   var liNummer = i + 1;
    //   var newLIText = document.createTextNode('Das ist Listeneintrag Nummer ' + liNummer);
    //   document.getElementById('liste').appendChild(newLI);
    //   document.getElementsByTagName('li')[i].appendChild(newLIText);
    

    console.dir(warenkorb);
    console.log(gesamtpreis);
    
}
// Element aus dem Warenkorb entfernen 

function loescheNr(id) {
    warenkorb.splice(id, 1);
    warenkorbAusgeben();
}
// Kontaktdaten leeren, wenn Button "Warenkorb leeren" geklickt
function deleteContact(){
    document.getElementById('Vorname').value = '';
    document.getElementById('Nachname').value = '';
    document.getElementById('Adresse').value = '';
}
// Warenkorb leeren, wenn Button "Warenkorb leeren" geklickt

function deleteCart(){
    warenkorb = [];
    warenkorbAusgeben();

}





function pushToDB(){

   
    // Prüfen ob Adresse eingegeben wurde
    if(document.getElementById('Adresse').value == ''){
        alert('Bitte Adresse eingeben!')
    }
    // Prüfen ob Vorname eingegeben wurde
    else if(document.getElementById('Vorname').value == ''){
        alert('Bitte Vorname eingeben!')
    }
    // Prüfen ob Nachname eingegeben wurde
    else if(document.getElementById('Nachname').value == ''){
        alert('Bitte Nachname eingeben!')
    }
    // Prüfen ob Pizzen ausgewählt wurde
    else if(warenkorb.length==0){
        alert('Bitte Pizzen auswählen!')
    }
    else{
      console.log('test121121');
     
      
        // Erstelle Object von Bestellung mit Kontaktdaten und Warenkorbinhalt
    var bestellung = {Adresse:document.getElementById('Adresse').value,
                    Vorname: document.getElementById('Vorname').value,
                    Nachname: document.getElementById('Nachname').value,
                    Pizzen: warenkorb}
                    


        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajaxtest.php', true);
        xhr.setRequestHeader('Content-type', 'application/json;=UTF-8');

        
        console.log(JSON.stringify(bestellung));
        xhr.send(JSON.stringify(bestellung));
        //xhr.abort();

        xhr.onreadystatechange = () => {
            if(this.readyState == 4  && this.status == 200){
                // alert(responseText);
            }
        }
  
        xhr.onload = function(){
        console.log(this.responseText);


        }
        alert('Vielen Dank für ihre Bestellung ---> Sie werden nun zur Übersicht weitergeleitet');
        window.open("http://localhost//EWA%20Praktikum%20/Praktikum%202/EWA/Seitenklasse_kunde.php"); 
        //alert('Vielen Dank für ihre Bestellung ---> Sie werden nun zur Übersicht weitergeleitet');
       
    } 
    
    }



