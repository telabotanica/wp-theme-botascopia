require('jquery-accessible-accordion-aria/jquery-accessible-accordion-aria.js');

// $(function () {
//     $('.js-accordion').accordion();
// });

// Récupère tous les éléments de classe "js-accordion"
var accordions = document.querySelectorAll('.js-accordion');

// Boucle sur tous les éléments de la liste
for (var i = 0; i < accordions.length; i++) {
    // Récupère le contenu de la section
    var content = accordions[i].nextElementSibling;

    // Masque le contenu par défaut
    content.style.display = 'none';

    // Ajoute un bouton pour ouvrir/fermer la section
    var button = document.createElement('button');
    button.textContent = 'Toggle section';
    button.setAttribute('aria-expanded', 'false');
    accordions[i].appendChild(button);

    // Ajoute un gestionnaire d'événements de clic pour le bouton
    button.addEventListener('click', function() {
        // Affiche ou masque la section correspondante en fonction de son état actuel
        if (content.style.display === 'block') {
            content.style.display = 'none';
            button.setAttribute('aria-expanded', 'false');
        } else {
            content.style.display = 'block';
            button.setAttribute('aria-expanded', 'true');
        }
    });

    // Ajoute les attributs ARIA appropriés à la section et au bouton
    accordions[i].setAttribute('role', 'presentation');
    content.setAttribute('role', 'region');
    content.setAttribute('aria-labelledby', button.id);
}
