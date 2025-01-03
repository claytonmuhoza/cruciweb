document.getElementById('inscriptionForm').addEventListener('submit', function(event) {
    // Récupérer les champs
    const usernameField = document.getElementById('username');
    const passwordField = document.getElementById('password');
    const username = usernameField.value;
    const password = passwordField.value;

    // Références des éléments d'erreur
    const usernameError = document.getElementById('usernameError');
    const passwordError = document.getElementById('passwordError');

    // Réinitialiser les messages d'erreur et les styles
    usernameError.textContent = '';
    usernameError.classList.remove('error');
    usernameError.classList.remove('message');

    passwordError.textContent = '';
    passwordError.classList.remove('error');
    passwordError.classList.remove('message');

    let isValid = true;

    // Vérification du nom d'utilisateur
    if (!username.trim()) {
        usernameError.textContent = "Le nom d'utilisateur ne doit pas être vide ou contenir uniquement des espaces.";
        usernameError.classList.add('error');
        usernameError.classList.add('message');
        isValid = false;
    } else if (/\s/.test(username)) {
        usernameError.textContent = "Le nom d'utilisateur ne doit pas contenir d'espace.";
        usernameError.classList.add('error');
        usernameError.classList.add('message');
        isValid = false;
    }

    // Vérification du mot de passe
    if (!password.trim()) {
        passwordError.textContent = "Le mot de passe ne doit pas être vide ou contenir uniquement des espaces.";
        passwordError.classList.add('error');
        passwordError.classList.add('message');
        isValid = false;
    } else if (password.length < 5) {
        passwordError.textContent = "Le mot de passe doit contenir au moins 5 caractères.";
        passwordError.classList.add('error');
        passwordError.classList.add('message');
        isValid = false;
    }

    // Si une erreur est détectée, empêcher l'envoi du formulaire
    if (!isValid) {
        event.preventDefault();
    }
});