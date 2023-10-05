require('../../style.css');
require ('jquery');
// Icons
require('../icons/_all.js');
// import { icons } from "./assets/icons/_all.js";

// Lazyload
// var LazyLoad = require('vanilla-lazyload');
// var lazy = new LazyLoad({
//     elements_selector: "iframe.lazyload, img.lazyload"
// });

// Modules
// Require all script.js files in the modules folder
var req = require.context('../../modules/', true, /script\.js$/);
req.keys().forEach(req);

// Blocks
// Require all script.js files in the blocks folder
// var req = require.context('../../blocks/', true, /script\.js$/);
// req.keys().forEach(req);

// Composants
// Require all script.js files in the components folder
var req = require.context('../../components/', true, /script\.js$/);
req.keys().forEach(req);

//Enregistre les collections favorites
document.addEventListener('DOMContentLoaded', function() {

    // Bouton retour renvoyant à la page précédente
    if (document.querySelector('.return-button')){
        document.querySelector('.return-button').addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    }

    setFavoris('.card-collection-icon','category');
    setFavoris('.single-collection-buttons', 'category');
    setFavoris('.single-collection-buttons', 'fiche');
    setFavoris('.card-fiche-icon', 'fiche');
    popupReserverFiche();
    envoyerFicheEnValidation();
    publierFiche();
    popupAjouterFiche();
    collectionSearchFiches();
    loadMoreCollections();
});

function setFavoris(selector, type){
    var collections= document.querySelectorAll(selector);
    collections.forEach(function(bouton) {
        bouton.addEventListener('click', function() {
            var user_id = this.getAttribute('data-user-id');
            var category = this.getAttribute('data-' + type + '-id');
            // var ma_valeur = 'valeur de test';
            var id = this.id; // récupère l'ID unique de l'élément HTML
            // Définit la variable ajaxurl en utilisant mon_ajax_object.ajax_url
            var ajaxurl = ajax_object.ajax_url;
            // Envoi de la requête AJAX pour mettre à jour la valeur de user_meta
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Si succès, on change l'icone
                        switch (selector){
                            case '.card-collection-icon':
                                var categoryHtml = document.querySelector('#'+id);
                                categoryHtml.querySelector(':first-child').classList.toggle('icon-star-outline');
                                categoryHtml.querySelector(':first-child').classList.toggle('icon-star');

                                if (categoryHtml.querySelector('use').getAttributeNS('http://www.w3.org/1999/xlink', 'href') == '#icon-star-outline'){
                                    categoryHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star');
                                } else {
                                    categoryHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star-outline');
                                }
                                break;

                            case  '.single-collection-buttons':
                                var favHtml = document.querySelector('#fav-'+ category);
                                favHtml.classList.toggle('outline');

                                var svg =  favHtml.querySelector('svg');
                                svg.classList.toggle('icon-star-outline');
                                svg.classList.toggle('icon-star');

                                if (svg.classList.contains('icon-color-vert-clair')){
                                    svg.classList.remove('icon-color-vert-clair');
                                    svg.classList.add('icon-color-blanc');
                                } else {
                                    svg.classList.remove('icon-color-blanc');
                                    svg.classList.add('icon-color-vert-clair');
                                }

                                if (favHtml.querySelector('use').getAttributeNS('http://www.w3.org/1999/xlink', 'href') == '#icon-star-outline'){
                                    favHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star');
                                } else {
                                    favHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star-outline');
                                }
                                break;

                            case '.card-fiche-icon':
                                var categoryHtml = document.querySelector('#'+id);
                                categoryHtml.querySelector(':first-child').classList.toggle('icon-star-outline');
                                categoryHtml.querySelector(':first-child').classList.toggle('icon-star');

                                if (categoryHtml.querySelector('use').getAttributeNS('http://www.w3.org/1999/xlink', 'href') == '#icon-star-outline'){
                                    categoryHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star');
                                } else {
                                    categoryHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star-outline');
                                }
                                break;
                        }
                    } else {
                        console.log('Erreur : ' + xhr.status);
                    }
                }
            };

            xhr.open('POST', ajaxurl);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            if (type == 'category'){
                xhr.send('action=set_fav_coll&user_id=' + user_id + '&category=' + category);
            } else {
                xhr.send('action=set_fav_fiche&user_id=' + user_id + '&fiche=' + category);
            }

        });
    });
};

// Affichage du popup pour réserver une fiche
function popupReserverFiche(){
    var fiches= document.querySelectorAll('.fiche-non-reserve');
    if (fiches) {
        fiches.forEach(function (fiche) {
            fiche.addEventListener('click', function () {
                var user_id = this.getAttribute('data-user-id');
                var ficheId = this.getAttribute('data-fiche-id');
                var ficheName = this.getAttribute('data-fiche-name');
                var ficheTitle = this.getAttribute('data-fiche-title');

                // Créer un élément de div pour afficher le contenu du popup
                var popupContenu = document.createElement(`div`);
                popupContenu.innerHTML = "<h2>Réserver la fiche " + ficheName + "</h2>" +
                    "<p>Cette fiche est disponible. Souhaitez-vous en devenir l'auteur ? Personne d'autre ne pourra y avoir accès" +
                    " tant que vous n'aurez pas envoyé le formulaire à vérification ou renoncé à la compléter.</p>" +
                    "<div class='popup-display-buttons'>" +
                    "<a class='button purple-button outline'><span class='button-text' id='annuler'>Annuler</span></a>" +
                    // "<a class='button green-button' href='"+ ficheUrl + "'><span class='button-text'>Réserver" +
                    "<a  class='button green-button' ><span" +
                    // " class='button-text' id='reserver-fiche' onclick='reserverFiche("+ ficheId +","+ user_id +")'>Réserver" +
                    " class='button-text' id='reserver-fiche'>Réserver" +
                    " la fiche</span></a>" +
                    "</div>";

                // Créer un élément de div pour le popup
                var popup = document.createElement('div');
                popup.classList.add('popup');
                popup.appendChild(popupContenu);

                // Ajouter le popup à la page
                document.querySelector('#content').classList.add('blur-background');
                document.body.appendChild(popup);

                // Ajouter un événement de clic pour fermer le popup
                document.addEventListener('click', function (event) {
                    var reserver = document.getElementById('reserver-fiche');
                    var annuler = document.getElementById('annuler');
                    if (event.target == reserver) {
                        popup.parentNode.removeChild(popup);
                        document.querySelector('#content').classList.remove('blur-background');

                        // Renvoie vers le formulaire et changement de propriétaire
                        window.location.href = '/formulaire/?p=' + ficheTitle + '&a=1';
                    }
                    if (event.target.classList.contains('blur-background') || event.target == annuler) {
                        popup.parentNode.removeChild(popup);
                        document.querySelector('#content').classList.remove('blur-background');
                    }
                });
            })
        })
    }
}

function envoyerFicheEnValidation(){
    const fiche = document.querySelector('#pending_btn');

    if (fiche){
        fiche.addEventListener('click', function() {
            var post_id = this.getAttribute('data-post-id');
            setStatus(post_id, 'pending');
        });
    }

}

function publierFiche(){
    const ficheAPublier = document.querySelector('#publish_btn');
    if (ficheAPublier){
        ficheAPublier.addEventListener('click', function() {
            var post_id = this.getAttribute('data-post-id');
            setStatus(post_id, 'publish');
        });
    }
}

function setStatus(postId, status) {
    var ajaxurl = ajax_object.ajax_url;

    var xhr = new XMLHttpRequest();
    // console.log('action=reserver_fiche&user_id=' + userId + '&fiche=' + ficheId);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Si succès,
                if (status == 'pending') {
                    console.log('Post set to pending');
                } else {
                    console.log('Post published');
                }
            } else {
                console.log('Erreur : ' + xhr.status);
            }
        }
    };

    xhr.open('POST', ajaxurl);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    if (status == 'pending'){
        xhr.send('action=set_fiche_status&post_id=' + postId + '&status=pending');
    } else {
        xhr.send('action=set_fiche_status&post_id=' + postId + '&status=publish');
    }

    setTimeout(function () { location.reload(); } , 1000 );
}

// Affichage de l'image sélectionnée lors de la création d'une collection
document.addEventListener('DOMContentLoaded', function() {
    const inputThumbnail = document.getElementById('post-thumbnail');
    const imagePreview = document.getElementById('image-preview');

    // Écoutez le changement de fichier
    inputThumbnail.addEventListener('change', function() {
        if (inputThumbnail.files && inputThumbnail.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Mettez à jour l'aperçu de l'image avec la nouvelle image sélectionnée
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };

            // Chargez le contenu de l'image sélectionnée
            reader.readAsDataURL(inputThumbnail.files[0]);
        }
    });
});

// On change la couleur du background pour l'id primary pour la page new-collection qui a un layout différent.
document.addEventListener("DOMContentLoaded", function() {
    // Sélectionnez l'élément avec la classe new-collection-main
    var newCollectionMain = document.querySelector('.new-collection-main');
    var profilMain = document.querySelector('.profil-main');

    // Sélectionnez l'élément avec l'ID primary
    var primaryElement = document.getElementById('primary');

    // Vérifiez si new-collection-main est présent
    if (newCollectionMain || profilMain) {
        // Changez le background-color de l'élément avec l'ID primary
        primaryElement.style.backgroundColor = 'var(--blanc)';
    }
});

// Ajout de fiches lors de la création d'une nouvelle collection
function popupAjouterFiche() {
    const ouvrirPopupButton = document.querySelector('#ouvrir_popup_ajouter_fiche');
    const formulaire = document.querySelector('#section-ajout-fiches');
if (ouvrirPopupButton) {
    ouvrirPopupButton.addEventListener("click", function (event) {
        event.preventDefault();

        var selectedCardIds = []; // Tableau pour stocker les IDs des cartes cochées
        const fiches = document.querySelectorAll('.card-selected');

        fiches.forEach(function (fiche) {
            let id = fiche.getAttribute('data-fiche-id');
            selectedCardIds.push(id);
        })

        var existingHiddenInput = document.querySelector('#fiches-selected');
        if (existingHiddenInput) {
            existingHiddenInput.value ='';
        }

        // Créer un champ de formulaire caché
        var hiddenInput = formulaire.querySelector('#fiches-selected');
        // var hiddenInput = document.createElement('input');
        // hiddenInput.id = 'fiches-selected'
        // hiddenInput.type = 'hidden';
        // hiddenInput.name = 'selectedCardIds';
        // formulaire.appendChild(hiddenInput);

        // Créer un élément de div pour le popup
        var popupAjoutFiches = document.createElement('div');
        popupAjoutFiches.classList.add('popup', 'popup-ajout-fiches');

        // Ajouter le popup à la page
        document.querySelector('#content').classList.add('blur-background');
        document.querySelector('header').classList.add('blur-background');

        // Créer un élément de div pour afficher le contenu du popup
        var popupAjoutContenu = document.createElement(`div`);
        popupAjoutContenu.innerHTML = '';
        popupAjoutContenu.innerHTML = "<h2>AJOUTER DES FICHES</h2>" +
            "<div class='search-box-wrapper search-box-ajout-fiche'>" +
            "<input type='text' class='ajout-fiches-search-bar search-box-input'" + " placeholder='Rechercher" +
            " une fiche'>" +
            // "<span class='search-box-button'><svg aria-hidden=\"true\" role=\"img\" class=\"icon icon-search \">" +
            // "<use xlink:href=\"#icon-search\"></use></svg></span>" +
            "</div>" +
            "<div class='popup-ajout-display-buttons'>" +
            "<a class='button purple-button outline'><span class='button-text'" +
            " id='annuler-ajout-fiches'>Annuler</span></a>" +
            "<a  class='button green-button' ><span" +
            " class='button-text' id='ajouter-fiche'>AJOUTER LES FICHES</span></a>" +
            "</div>";
        popupAjoutContenu.classList.add('popup-ajout-fiches-content');

        // On charge le contenu de la popup
        var content = loadContent(selectedCardIds, '?action=load_popup_content');
        popupAjoutContenu.appendChild(content);

        // On ajoute le contenu à l'intérieur du popup'
        popupAjoutFiches.appendChild(popupAjoutContenu);
        // On ajoute le popup au body
        document.body.appendChild(popupAjoutFiches);

        // Gestion de la fermeture du popup
        document.addEventListener('click', function (event) {
            const ajouter = document.querySelector('#ajouter-fiche');
            const annuler = document.querySelector('#annuler-ajout-fiches');
            if (event.target == ajouter) {
                popupAjoutFiches.parentNode.removeChild(popupAjoutFiches);
                document.querySelector('#content').classList.remove('blur-background');
                document.querySelector('header').classList.remove('blur-background');
                hiddenInput.value = JSON.stringify(selectedCardIds);
                displaySelectedFiches(selectedCardIds);
            }
            if (event.target.classList.contains('blur-background') || event.target == annuler) {
                popupAjoutFiches.parentNode.removeChild(popupAjoutFiches);
                document.querySelector('#content').classList.remove('blur-background');
                document.querySelector('header').classList.remove('blur-background');
                hiddenInput.value = JSON.stringify(selectedCardIds);
                displaySelectedFiches(selectedCardIds);
            }
        });

        // Handle search input change using event delegation
        document.addEventListener('input', function (event) {
            if (event.target.classList.contains('ajout-fiches-search-bar')) {
                setTimeout(function () {
                    const searchTerm = event.target.value.trim();
                    // Clear existing content before loading new content
                    var cardContainer = document.querySelector('#card-container-popup');
                    if (cardContainer) {
                        cardContainer.remove()
                    }
                    const updatedContent = loadContent(selectedCardIds, '?action=load_popup_content&search=' + encodeURIComponent(searchTerm));

                    popupAjoutContenu.appendChild(updatedContent); // Append updated content
                }, 100);
            }
        });
    });
}
}

//Affichage des fiches sur le popup ajout de fiches
function displaySelectedFiches(selectedIds){
    // Envoyer une requête AJAX pour récupérer les publications correspondantes
    let selectedIdsString = selectedIds.join(",")
    var xhrPosts = new XMLHttpRequest();
    xhrPosts.onreadystatechange = function () {
        if (xhrPosts.readyState === XMLHttpRequest.DONE) {
            if (xhrPosts.readyState === 4 && xhrPosts.status === 200) {
                var response = JSON.parse(xhrPosts.responseText);
                // Afficher les publications dans la balise HTML
                var existingFiches = document.querySelector('.existing-fiches');
                existingFiches.innerHTML = ''; // Efface le contenu précédent

                response.forEach(function (post) {
                    var postElement = document.createElement('div');
                    postElement.classList.add('card');
                    postElement.classList.add('card-fiche');
                    postElement.classList.add('card-selected');
                    postElement.setAttribute("data-fiche-id", post.id);

                    postElement.innerHTML = `
                    <a data-fiche-id="${post.id}">
                        <img src="${post.image}" alt="photo de ${post.name}" class="card-fiche-image" title="${post.name}">
                    </a>
                    <div class="card-fiche-body">
                        <a><span class="card-fiche-title">${post.name}</span>
                        <span class="card-fiche-espece">${post.species}</span>
                        </a>
                    </div>`;

                    existingFiches.appendChild(postElement);
                });
            } else {
                console.log('Erreur lors de la récupération des publications : ' + xhrPosts.status);
            }
        }
    };

    var ajaxurlPosts = ajax_object.ajax_url + "?action=get_selected_posts&selected_ids=" + selectedIdsString;
    xhrPosts.open('GET', ajaxurlPosts, false);
    xhrPosts.send();
}

//Affichage des fiches sur le popup ajout de fiches
function loadContent(selectedCardIds, ajaxFunction){
    // Créer un élément de div pour afficher le contenu du popup
    var cardContainer = document.createElement('div');
    cardContainer.classList.add('display-fiches-cards-items');
    cardContainer.classList.add('card-container');
    cardContainer.id = 'card-container-popup'
    cardContainer.innerHTML = '';

    var ajaxurl = ajax_object.ajax_url;
    // Envoi de la requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var jsonData = JSON.parse(xhr.responseText);
                // totalPages = Math.ceil(jsonData.length / 5);

                // On remplit les infos de chaque card (on ajoute les cards au cardContainer)
                jsonData.forEach(function (item) {
                    var card = document.createElement('div');
                    card.classList.add('card');
                    card.classList.add('card-fiche');

                    // Vérifier si l'ID de l'élément est déjà dans le tableau selectedCardIds
                    var isChecked = selectedCardIds.includes(String(item.id));

                    card.innerHTML = `
                        <div class="checkbox-area">
                        <input id="checkbox-${item.id}" type="checkbox" class="card-checkbox" data-fiche-id="${item.id}" ${isChecked ? 'checked' : ''}>
                        <label for="checkbox-${item.id}" class="checkbox-container"></label>
                        </div>
                        <a data-fiche-id="${item.id}">
                            <img src="${item.image}" alt="photo de ${item.name}" class="card-fiche-image" title="${item.name}">
                        </a>
                        <div class="card-fiche-body">
                            <a><span class="card-fiche-title">${item.name}</span>
                            <span class="card-fiche-espece">${item.species}</span>
                            </a>
                        </div>`;

                    cardContainer.appendChild(card);

                    // Écouter les changements de checkbox
                    var checkbox = card.querySelector('.card-checkbox');
                    if (checkbox) {
                        checkbox.addEventListener('change', function () {
                            var ficheId = checkbox.dataset.ficheId;

                            if (checkbox.checked) {
                                // Ajouter l'ID à la liste si la checkbox est cochée
                                selectedCardIds.push(ficheId);
                            } else {
                                // Retirer l'ID de la liste si la checkbox est décochée
                                var index = selectedCardIds.indexOf(ficheId);
                                if (index !== -1) {
                                    selectedCardIds.splice(index, 1);
                                }
                            }
                        });
                    }
                });
            } else {
                console.log('Erreur : ' + xhr.status);
            }
        }
    }

    xhr.open('GET', ajaxurl + ajaxFunction, false);
    xhr.send();

    return cardContainer;
}

// Recherche de fiches sur la page single collection
function collectionSearchFiches() {
    var searchForm = document.querySelector('#single-collection-search');

    if (searchForm) {
        var searchButton = searchForm.querySelector('#search-button .button-text')
        var post = searchForm.dataset.post;
        var searchInput = searchForm.querySelector('.search-box-input');

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(searchForm, post);
            }
        });
    }

    if (searchButton) {
        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            performSearch(searchForm, post);
        });
    }
}

// Recherche de fiches sur la page single collection
function performSearch(searchForm, post) {
    var searchValue = searchForm.querySelector('.search-box-input').value;
    var cardContainer = document.querySelector('#single-collection-fiches-container');
    cardContainer.innerHTML = '';
    var ajaxurl = ajax_object.ajax_url;
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.querySelector('#single-collection-pagination').innerHTML = '';
                if (searchValue) {
                    var jsonData = JSON.parse(xhr.responseText);

                    jsonData.forEach(function (item) {
                        var card = document.createElement('div');
                        card.classList.add('fiche-status');

                        card.innerHTML = `
                        <div class="${item.fichesClasses}">
                                ${item.ficheStatusText}
                        </div>
                        
                        <div class="card-fiche card">
                            <a href="${item.href}" class="${item.popup}" data-user-id="${item["data-user-id"]}" 
                            data-fiche-id = "${item["data-fiche-id"]}"
                            data-fiche-name="${item["data-fiche-name"]}" data-fiche-url="${item["data-fiche-url"]}" data-fiche-title="${item["data-fiche-title"]}">
                                <img src="${item.image}" class="card-fiche-image" alt="image-plante" title="${"data-fiche-name"}"/>
                            </a>
                            <div class="card-fiche-body">
                                <a href="${item.href}" class="${item.popup}" data-user-id="${item["data-user-id"]}" 
                            data-fiche-id = "${item["data-fiche-id"]}"
                            data-fiche-name="${item["data-fiche-name"]}" data-fiche-url="${item["data-fiche-url"]}" data-fiche-title="${item["data-fiche-title"]}">
                                    <span class="card-fiche-title">${item.name}</span>
                                    <span class="card-fiche-espece">${item.species}</span>
                                </a>
                            </div> 
                            <div class="card-fiche-icon" data-user-id="${item["data-user-id"]}" 
                            data-fiche-id = "${item["data-fiche-id"]}"
                            data-fiche-name="${item["data-fiche-name"]}" data-fiche-url="${item["data-fiche-url"]}" data-fiche-title="${item["data-fiche-title"]}" id="${item.id}">
                                <svg aria-hidden="true" role="img" class="icon icon-${item.icon.icon} ${item.icon.color}">
                                <use xlink:href="#icon-${item.icon.icon}"></use></svg>
                            </div>
                        </div>
                        `;
                        cardContainer.appendChild(card);
                        setFavoris('.card-fiche-icon', 'fiche');
                    });
                } else {
                    // Si pas de recherche ou effacement de la recherche, on réouvrir la page
                    window.location.href = xhr.responseText;
                }
            } else {
                console.log('Erreur lors de la récupération des fiches : ' + xhr.status);
            }
        }
    }

    xhr.open('GET', ajaxurl + '?action=load_collection_content&post=' + post + '&search=' + encodeURIComponent(searchValue), true);
    xhr.send();
}


function loadMoreCollections() {
    var loadMoreButton = document.getElementById('loadMoreCollections');
    var collectionsContainer = document.getElementById('collections-container');

    if (loadMoreButton && collectionsContainer){
        // Hide all collections except the first 10 initially
        for (var i = 10; i < collectionsContainer.children.length; i++) {
            collectionsContainer.children[i].style.display = 'none';
        }

        loadMoreButton.addEventListener("click", function () {
            var hiddenCollections = collectionsContainer.querySelectorAll(':scope > div[style*="display: none"]');

            hiddenCollections.forEach(function (collection, index) {
                // Show the next 5 collections
                if (index < 10) {
                    collection.style.display = 'flex';
                }
            });

            // If all collections are now visible, hide the "Voir plus" button
            if (hiddenCollections.length <= 10) {
                loadMoreButton.style.display = 'none';
            }
        });
    }
}
