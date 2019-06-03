var warenkorb = [];

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
     warenkorb.sort();
        for (var i = 0; i < warenkorb.length; ++i) {
            
            gesamtpreis+=warenkorb[i].price;
            

            // var elm = document.getElementById('price'+(i+1));
            // var first = elm.dataset.price;
            // gesamtpreis+=first;
            ausgabe += '<li id="nr' + i + '">';
            ausgabe += '<span class = "pizza123">' + warenkorb[i].name +'</span>';
            ausgabe += '<input type="button" value="X" id="loeschen" onclick="loescheNr(' + i + ');" />';
            ausgabe += '</li>';
    }
    
    document.getElementById('liste').innerHTML = ausgabe;
    document.getElementById('Gesamtpreis').innerHTML = gesamtpreis.toFixed(2);

    var element = document.getElementById('list1');
    while (element.firstChild) {
      element.removeChild(element.firstChild);
    }
  
    for (var i = 0; i < 10; i++) {
      var newLI = document.createElement('li');
      var liNummer = i + 1;
      var newLIText = document.createTextNode('Das ist Listeneintrag Nummer ' + liNummer);
      document.getElementById('liste').appendChild(newLI);
      document.getElementsByTagName('li')[i].appendChild(newLIText);
    }

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
        // Erstelle Object von Bestellung mit Kontaktdaten und Warenkorbinhalt
    var bestellung = {Adresse:document.getElementById('Adresse').value,
                    Vorname: document.getElementById('Vorname').value,
                    Nachname: document.getElementById('Nachname').value,
                    Pizzen: warenkorb}
                    


        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajaxtest.php', true);
        xhr.setRequestHeader('Content-type', 'application/json;=UTF-8');

        

        xhr.send(JSON.stringify(bestellung));
        xhr.abort();

        xhr.onreadystatechange = () => {
            if(this.readyState == 4  && this.status == 200){
                // alert(responseText);
            }
        }
  
        xhr.onload = function(){
        console.log(this.responseText);
        }
  
    } 
   
    }



