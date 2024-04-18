document.addEventListener('DOMContentLoaded', function() {
    
        prepareToProfile();
        refreshPage();
        
});


//Pour la page profil
function changeStatusAdmin(user,event){
    event.preventDefault();
	var id = user.id;
	var mode=user.modeIn;
	
	var httpc = new XMLHttpRequest();
	var url = document.querySelector("#routeAdmin").value;
    var erreur="Une erreur est survenue.";
	httpc.open("PUT", url, true);
 	httpc.setRequestHeader("Content-type", "application/json; charset=utf-8");
	var data = {'id':id,'mode':mode};
 	httpc.send(JSON.stringify(data));
	httpc.onload = function() {
		if (httpc.readyState == XMLHttpRequest.DONE) {
			// Check the status of the response
			if (httpc.status == 200) {
			// Access the data returned by the server
				var msg = httpc.response;
                var user = JSON.parse(msg);
                var nom =user.nom;
                var email = user.email;
                var mode = user.mode;
				var message="";
                var debut="L'utilisateur "+nom+" ("+email+")";
				if (mode===1){
					message = debut +" est bien devenu vérificateur.";
					
				}else if(mode===2){
					message = debut+" est bien devenu contributeur.";

				}else{
					message = erreur;
				}

				popupMessageProfil(message);
				
				
			} else {
				popupMessageProfil(erreur);
			}
		}
	};
				
}

function changeStatusRedac(user,event){
    event.preventDefault();
    //valeur de searchbar
	var email = user.email;
	var httpc = new XMLHttpRequest();
	var url = document.querySelector("#routeRedac").value;
	httpc.open("PUT", url, true);
 	httpc.setRequestHeader("Content-type", "application/json; charset=utf-8");
	var data = {'email':email.trim()};
 	httpc.send(JSON.stringify(data));
	httpc.onload = function() {
		if (httpc.readyState == XMLHttpRequest.DONE) {
			// Check the status of the response
			if (httpc.status == 200) {
			// Access the data returned by the server
				var msg = httpc.response;
                console.log(msg);
				var user = JSON.parse(msg);
                var nom =user.nom;
                var email = user.email;
                var mode = user.mode;
				var message="";
                var debut="L'utilisateur "+nom+" ("+email+")";
				if (mode===1){
					message=debut+" est bien devenu vérificateur.";
				}else if(mode===2){
					message = debut+" est déjà vérificateur ou ne peut le devenir.";
				}else if(mode===3){
					message = "Cet utilisateur n'existe pas.";
				}
				popupMessageProfil(message);
				
			} else {
				popupMessageProfil("Erreur");
			}
		}
	};
    
}

function prepareToProfile(){
	var cpt_elmt = document.querySelector("#cpt");
	if(cpt_elmt){
		var cpt_str=document.querySelector("#cpt").value;
		var cpt = parseInt(cpt_str);
	}

	
	for (i=0;i<=cpt;i++){
		var btn_to_redac = document.querySelector("#changeToEditor_"+i);
		if (btn_to_redac){
			btn_to_redac.addEventListener("click", function(){checkUser(this,event);}); 
		}
		var btn_to_contrib = document.querySelector("#changeToContrib_"+i);
		if (btn_to_contrib){
			btn_to_contrib.addEventListener("click", function(){checkUser(this,event);}); 
		}
		
	}
	var element = document.querySelector("#search-button");
    
	if (element){
        
		element.addEventListener("click", function(event){checkUser(this,event);}); 
        console.log(element);
        
	}
	
}

function popupMessageProfil(message){
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
        var ok = document.getElementById('ok');
        if (event.target == ok) {
            popup.parentNode.removeChild(popup);
            document.querySelector('#content').classList.remove('blur-background');
            location.reload();
        }
        
    });
}

function checkUser(e,event){
    event.preventDefault();
    var email="";
    if (document.getElementsByName("q")[0]){
        email = document.getElementsByName("q")[0].value;
    }
    var id = 0;
	var modeIn=0;
    if (e.id){
        
        if (e.id.includes('Editor')){
            modeIn=1;
            id=e.value;
        }else if(e.id.includes('Contrib')){
            modeIn=2;
            id=e.value;
        }
    }
	
	var httpc = new XMLHttpRequest();
	var url = document.querySelector("#routeCheck").value;
	httpc.open("PUT", url, true);
 	httpc.setRequestHeader("Content-type", "application/json; charset=utf-8");
	var data = {'id':id,'email':email.trim(),'mode':modeIn};
    console.log(data);
 	httpc.send(JSON.stringify(data));
	httpc.onload = function() {
		if (httpc.readyState == XMLHttpRequest.DONE) {
			// Check the status of the response
			if (httpc.status == 200) {
			// Access the data returned by the server
				var msg = httpc.response;
				var user = JSON.parse(msg);
                var nom =user.nom;
                var email = user.email;
                var mode = user.mode;
				var message="";
                var debut="L'utilisateur "+nom+" ("+email+")";
                var fin=" Voulez-vous continuer ?"
				if (mode===1){
					message=debut+" deviendra vérificateur."+fin;
				}else if(mode===2){
					message = debut+" deviendra contributeur."+fin;
				}else if(mode===3){
					message = "Cet utilisateur n'existe pas.";
				}else if(mode===4){
                    message = debut+ " a déjà le rôle que vous voulez lui donner ou ne peut l'obtenir.";
                }
                user.message=message;
                user.modeIn=modeIn;
				popupMessageConfirmation(user);
				
			} else {
				popupMessageProfil("Erreur");
			}
		}
	};
    
}

function popupMessageConfirmation(user){
    console.log(user);
    // Créer un élément de div pour afficher le contenu du popup
    var popupContenu = document.createElement(`div`);
    popupContenu.setAttribute('id','contenu');
    popupContenu.innerHTML = 
        "<p>"+user.message+"</p>" +
        "<div id='boutons' class='popup-display-buttons'>" +
        "<div><a id='cancel_a' class='button purple-button' ><span" +
        " class='button-text' id='cancel'>Annuler" +
        " </span></a><a id='ok_a' class='button green-button' ><span" +
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
    var cancel = document.getElementById("cancel");
    var ok = document.getElementById('ok');
    // Ajouter un événement de clic pour fermer le popup
    document.addEventListener('click', function (event) {
        
        
        if (event.target == ok) {
            popup.parentNode.removeChild(popup);
            document.querySelector('#content').classList.remove('blur-background');
            if(user.modeIn===0){
                changeStatusRedac(user,event)
            }else{
                changeStatusAdmin(user,event);
            }
            
        }
        if (event.target == cancel) {
            popup.parentNode.removeChild(popup);
            document.querySelector('#content').classList.remove('blur-background');
       
        }
        
    });
    if(user.mode === 3 || user.mode === 4){

        document.getElementById('ok_a').setAttribute("class","hidden");
    }
}

function refreshPage(){
    var path = window.location.href;
    var pieces = path.split("/");
    var newpath = '/'+ pieces[3]+"/"+pieces[4];
    var beg_path="/formulaire/?p=bdtfx-nn-";
    var end_path="&a=1";
    
    if (newpath.startsWith(beg_path) && newpath.endsWith(end_path)){
        pieces = newpath.split("&");
        console.log(pieces);
        newpath = pieces[0];
        window.location.href=newpath;
    }
    
}
