# Thème Wordpress du site botascopia

Ce thème utilise le bundler [Webpack](https://webpack.github.io) et l'outil de
gestion de dépendances [Composer](https://getcomposer.org).

Ce thème a été crée sous PHP 8.

## Pour débuter

- Installer [Node](https://nodejs.org)

- Installer le plugin wordpress `Advanced Custom Fields`.

- Installer le plugin wordpress `Posts 2 Posts`.

- Cloner le plugin [tela sso](https://github.com/telabotanica/wp-plugin-bs-sso) dans le dossier wp-content/plugins et l'activer

- Installer le plugin `WP Mail SMTP` et configurer le serveur mail.

Installer les dépendences du projet

    npm install
    composer install


### Pendant le développement

    npm run start

Cette commande :
- surveille les fichiers du thème
- recompile automatiquement à chaque modification

### Compiler le thème

    npm run build

Cette commande :
- compile `assets/main.css` dans `dist/bundle.css`
- compile `assets/scripts/main.js` dans `dist/bundle.js`
- inclut en inline les images SVG utilisées dans les feuilles de style

### Déployer le thème avec git

Depuis le serveur :

  - git pull
  - npm install
  - composer install
