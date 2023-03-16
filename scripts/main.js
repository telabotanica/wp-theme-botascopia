require('../style.css');

// Icons
require('../assets/icons/_all.js');

// Modules
// Require all script.js files in the modules folder
var req = require.context('../modules/', true, /script\.js$/);
req.keys().forEach(req);

// Blocks
// Require all script.js files in the blocks folder
var req = require.context('../blocks/', true, /script\.js$/);
req.keys().forEach(req);

// Composants
// Require all script.js files in the components folder
var req = require.context('../components/', true, /script\.js$/);
req.keys().forEach(req);