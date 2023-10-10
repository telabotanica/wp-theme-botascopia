document.addEventListener("DOMContentLoaded", function () {
    var menuToggle = document.getElementById("menu-toggle");
    var headerNav = document.querySelector(".header-nav-usecases");
    let loginNav = document.querySelector(".header-login");
    let menuContainer = document.querySelector(".menu-container");
    let deco = document.querySelector(".deconnexion-button");

    if (menuToggle && headerNav) {
        toggleElementsVisibility(headerNav);
        toggleElementsVisibility(loginNav);

        window.addEventListener("resize", function () {
            toggleElementsVisibility(headerNav);
            toggleElementsVisibility(loginNav);
            toggleElementsVisibility(deco);
            document.querySelector('#primary').classList.remove('blur-background');
            menuContainer.classList.remove("bg-rose");
            menuContainer.classList.remove("flex");
        });

        menuToggle.addEventListener("click", function () {
            headerNav.classList.toggle("hidden");
            loginNav.classList.toggle("hidden");
            deco.classList.toggle("hidden");
            menuContainer.classList.toggle("bg-rose");
            menuContainer.classList.toggle("flex");
            document.querySelector('#primary').classList.toggle('blur-background');
        });
    }
});

function toggleElementsVisibility(el) {
    var screenWidth = window.innerWidth;

    // Ajoutez ou retirez la classe .hidden en fonction de la largeur de l'écran
    if (screenWidth <= 780) {
        el.classList.add("hidden");
    } else {
        el.classList.remove("hidden");
    }
}