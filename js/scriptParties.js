console.log(creneaux);
console.log(donnees);
//on verif si a la date du jour on a un creneau
function checkdate(event) {
    var jour = event.value;
    var creneauxdiv = document.getElementById("creneaux");
    
    creneauxdiv.innerHTML = "";
    var selected = document.getElementById("selected");
    selected.value = "0";
    var lastselected = document.getElementById(selected.value);
    for (var i = 0; i < creneaux.length; i++) {
        if (creneaux[i].jour == jour && creneaux[i].id_medecin ==  donnees.medecin) {
            console.log("test");
            if (creneaux[i].est_disponible == 1) {
                creneauxdiv.innerHTML += "<a class='dispo'id='"+creneaux[i].id_creneau+"' onclick='selected(this);'>"+creneaux[i].heure+"</a></br>";
            }
            else {
                creneauxdiv.innerHTML += "<a class='indisponible' id='"+creneaux[i].id_creneau+"'>"+creneaux[i].heure+"</a></br>";
            }
        }
    }
}

function selected(event) {


    var selectionne = document.getElementById(event.id);

    var selected = document.getElementById("selected");

    if(selected.value != "0"){

    var lastselected = document.getElementById(selected.value);
    lastselected.style.backgroundColor = "#00FF00";

    }
    selectionne.style.backgroundColor = "yellow";


    selected.value = event.id;


}