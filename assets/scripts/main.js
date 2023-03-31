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
    var collections= document.querySelectorAll('.card-collection-icon');
    collections.forEach(function(bouton) {
        bouton.addEventListener('click', function() {
            var user_id = this.getAttribute('data-user-id');
            var category = this.getAttribute('data-category-id');
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
                        var categoryHtml = document.querySelector('#'+id);
                        categoryHtml.querySelector(':first-child').classList.toggle('icon-star-outline');
                        categoryHtml.querySelector(':first-child').classList.toggle('icon-star');

                        if (categoryHtml.querySelector('use').getAttributeNS('http://www.w3.org/1999/xlink', 'href') == '#icon-star-outline'){
                            categoryHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star');
                        } else {
                            categoryHtml.querySelector('use').setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', '#icon-star-outline');
                        }

                    } else {
                        console.log('Erreur : ' + xhr.status);
                    }
                }
            };

            xhr.open('POST', ajaxurl);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('action=set_fav_coll&user_id=' + user_id + '&category=' + category);


        });
    });
});



