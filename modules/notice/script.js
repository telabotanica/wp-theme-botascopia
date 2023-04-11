// Définir l'objet Tela et son propriétaire modules
var Tela = window.Tela || {};
Tela.modules = Tela.modules || {};

// Définir le module notice
Tela.modules.notice = function(selector) {
  // Récupérer l'élément HTML correspondant au sélecteur
  if (document.querySelector(selector)) {
    var el = document.querySelector(selector),
        closeButton;

    function init() {
      closeButton = el.querySelector('.notice-close');

      closeButton.addEventListener('click', onClickCloseButton);
    }

    function onClickCloseButton(e) {
      e.preventDefault();
      el.style.display = 'none';
    }

    init();

    return el;
  } else {
    return null;
  }
};

// Initialiser le module notice lorsque le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
  Tela.modules.notice('.notice');
});

