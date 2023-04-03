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
var req = require.context('../../blocks/', true, /script\.js$/);
req.keys().forEach(req);

// Composants
// Require all script.js files in the components folder
var req = require.context('../../components/', true, /script\.js$/);
req.keys().forEach(req);

//Enregistre les collections favorites
document.addEventListener('DOMContentLoaded', function() {

    setFavoris('.card-collection-icon','category');
    setFavoris('.single-collection-buttons', 'category');
    setFavoris('.card-fiche-icon', 'fiche');
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
                                console.log(favHtml, '#fav-'+ category)
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



