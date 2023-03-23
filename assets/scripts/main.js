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