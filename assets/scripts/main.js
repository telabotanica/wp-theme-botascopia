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
    setFavoris('.card-fiche-icon', 'fiche');
    popupReserverFiche();
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
    fiches.forEach(function(fiche){
        fiche.addEventListener('click', function (){
            var user_id = this.getAttribute('data-user-id');
            var ficheId = this.getAttribute('data-fiche-id');
            var ficheName = this.getAttribute('data-fiche-name');
            var ficheUrl = this.getAttribute('data-fiche-url');

            // Créer un élément de div pour afficher le contenu du popup
            var popupContenu = document.createElement(`div`);
            popupContenu.innerHTML = "<h2>Réserver la fiche " + ficheName + "</h2>" +
                "<p>Cette fiche est disponible, vous pouvez indiquer que vous commencez à travailler dessus, pour" +
                " éviter qu’une autre personne travaille dessus en même temps.</p>" +
                "<div class='popup-display-buttons'>" +
                "<a class='button purple-button outline'><span class='button-text' id='annuler'>Annuler</span></a>" +
                // "<a class='button green-button' href='"+ ficheUrl + "'><span class='button-text'>Réserver" +
                "<a  class='button green-button' ><span" +
            // " class='button-text' id='reserver-fiche' onclick='reserverFiche("+ ficheId +","+ user_id +")'>Réserver" +
            " class='button-text' id='reserver-fiche'>Réserver" +
                " la fiche</span></a>" +
                "</div>" ;

            // Créer un élément de div pour le popup
            var popup = document.createElement('div');
            popup.classList.add('popup');
            popup.appendChild(popupContenu);

            // Ajouter le popup à la page
            document.querySelector('#content').classList.add('blur-background');
            document.body.appendChild(popup);

            // Ajouter un événement de clic pour fermer le popup
            document.addEventListener('click', function(event) {
                var reserver = document.getElementById('reserver-fiche');
                var annuler = document.getElementById('annuler');
                if ( event.target == reserver) {
                    reserverFiche(ficheId, user_id);
                    popup.parentNode.removeChild(popup);
                    document.querySelector('#content').classList.remove('blur-background');
                    //TODO Effectuer la redirection vers le formulaire
                }
                if (event.target.classList.contains('blur-background') || event.target == annuler) {
                    popup.parentNode.removeChild(popup);
                    document.querySelector('#content').classList.remove('blur-background');
                }
            });
        })
    })
}

function reserverFiche(ficheId, userId){
// Mettre à jour les données de la fiche dans la base de données

    // Définit la variable ajaxurl en utilisant mon_ajax_object.ajax_url
    var ajaxurl = ajax_object.ajax_url;

    // Envoi de la requête AJAX pour mettre à jour la valeur du post
    var xhr = new XMLHttpRequest();
    console.log('action=reserver_fiche&user_id=' + userId + '&fiche=' + ficheId);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Si succès,
               console.log('changement de propriétaire de la fiche ' + ficheId);
            } else {
                console.log('Erreur : ' + xhr.status);
            }
        }
    };

    xhr.open('POST', ajaxurl);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=reserver_fiche&user_id=' + userId + '&fiche=' + ficheId);
}