// Require all *.svg files in the current folder
var req = require.context('./', true, /.*\.svg$/);
req.keys().forEach(req);

// function loadSvg(url, callback) {
//     var xhr = new XMLHttpRequest();
//     xhr.onreadystatechange = function() {
//         if (xhr.readyState === 4 && xhr.status === 200) {
//             callback(xhr.responseXML.documentElement);
//         }
//     };
//     xhr.open('GET', url, true);
//     xhr.setRequestHeader('Content-type', 'image/svg+xml');
//     xhr.send();
// }
//
// // Chemin vers le répertoire contenant les fichiers SVG
// var svgDir = './wp-content/themes/wp-theme-botascopia/assets/icons/';
//
// // Liste des fichiers SVG à charger
// var svgFiles = ['twitter.svg', 'plus.svg', 'tool.svg'];
//
// // Parcourir la liste des fichiers SVG
// svgFiles.forEach(function(file) {
//     // Charger le fichier SVG avec AJAX
//     loadSvg(svgDir + file, function(svg) {
//         // Insérer le contenu SVG dans le document
//         document.body.appendChild(svg);
//     });
// });
