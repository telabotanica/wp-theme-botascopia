document.addEventListener('DOMContentLoaded', function() {
    let loginForm = document.querySelector('#loginform');

    if (loginForm){
        let infoText = document.createElement('p');
        infoText.classList.add('login-info-text');
        infoText.innerHTML = "Si vous avez déjà un compte sur Tela Botanica veuillez utiliser vos identifiants" +
            " Tela Botanica pour vous connecter";
        loginForm.appendChild(infoText);
    }
});