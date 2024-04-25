function displayPopup(elem,ok_id){
    var attribute = elem.getAttribute('class');
    if (attribute !== "fiche-non-reserve"){
        var author= elem.getAttribute("data-author");
        var role = elem.getAttribute("data-role");
        if (role === "contributor"){
            popupMessage("Cette fiche est en train d'être rédigée par "+author+". Elle sera accessible bientôt.",ok_id);
        }
        
    }
}

function addClick(cartes,ok_id){
    cartes.forEach(function (item) {
        item.addEventListener('click', function () {
            displayPopup(item,ok_id);
            console.log(item);
        });
    });
}

function prepareToCollection(){
    var cartes = document.querySelectorAll(".card-fiche a");
    var cartes2 = document.querySelectorAll(".card-fiche-body a");
    addClick(cartes,"ok");
    addClick(cartes2,"okay");
}

document.addEventListener('DOMContentLoaded', function() {
    
    prepareToCollection();

});

function popupMessage(message,ok_id){
    // Créer un élément de div pour afficher le contenu du popup
    var popupContenu = document.createElement(`div`);
    popupContenu.innerHTML = 
        "<p>"+message+"</p>" +
        "<div class='popup-display-buttons'>" +
        "<div><a  class='button green-button' ><span" +
        " class='button-text' id='"+ok_id+"'>Continuer" +
        " </span></a></div>" +
        "</div>";

    // Créer un élément de div pour le popup
    var popup = document.createElement('div');
    popup.classList.add('popup');
    popup.classList.add('popup-reserver-fiche');
    popup.appendChild(popupContenu);

    // Ajouter le popup à la page
    document.querySelector('#content').classList.add('blur-background');
    document.body.appendChild(popup);

    // Ajouter un événement de clic pour fermer le popup
    
    var ok = document.getElementById(ok_id);
    ok.addEventListener("click",function(){
     
        location.reload();
    });
        
        
    
}

