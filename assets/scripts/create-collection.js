document.addEventListener('DOMContentLoaded', function() {
    
    prepareToCreateCollection();
});

function deleteFiche(elem){
    event.preventDefault();
    var post_id = elem.id;
	var httpc = new XMLHttpRequest();
	var collection_id = document.querySelector("#collection_id").value;
    var url = document.querySelector("#routeDelete").value;
	httpc.open("DELETE", url, true);
 	httpc.setRequestHeader("Content-type", "application/json; charset=utf-8");
	var data = {'post_id':post_id,'collection_id':collection_id};
    console.log(data);
 	httpc.send(JSON.stringify(data));
	httpc.onload = function() {
		if (httpc.readyState == XMLHttpRequest.DONE) {
			
			if (httpc.status == 200) {
			// Access the data returned by the server
				var msg = httpc.response;
				var obj = JSON.parse(msg);

                var mode = obj.mode;
                var nom = obj.nom;
                if (mode===1){
                    
                    var message = "La fiche "+ nom + " a bien été retirée de votre collection.";
                    popupMessage(message);
                }else{
                    popupMessage("Erreur");
                }
				
				
			} else {
				popupMessage("Erreur");
			}
		}
	};
}

function addClick(cartes){
    cartes.forEach(function (item) {
        item.addEventListener('click', function () {
            deleteFiche(item);
            
        });
    });
}

function prepareToCreateCollection(){
    var cartes = document.querySelectorAll(".card-selected .cross");
    addClick(cartes);
    
}

function popupMessage(message){
    // Créer un élément de div pour afficher le contenu du popup
    var popupContenu = document.createElement(`div`);
    popupContenu.innerHTML = 
        "<p>"+message+"</p>" +
        "<div class='popup-display-buttons'>" +
        "<div><a  class='button green-button' ><span" +
        " class='button-text' id='ok'>Continuer" +
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
    document.addEventListener('click', function (event) {
        event.preventDefault();
        var ok = document.getElementById('ok');
        
        console.log(event.target);
        if (event.target == ok) {

            popup.parentNode.removeChild(popup);
            document.querySelector('#content').classList.remove('blur-background');
            location.reload();
        }
        
    });
}

