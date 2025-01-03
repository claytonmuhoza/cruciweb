#!/bin/bash

# Chemin du fichier de configuration Apache
APACHE_CONF="/etc/apache2/apache2.conf"

# Texte à ajouter à la fin du fichier de configuration
APACHE_CONFIG_BLOCK="<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>"

# Chemin du fichier .htaccess
HTACCESS_FILE="/var/www/html/.htaccess"

# Chemin du fichier index.html
INDEX_FILE="/var/www/html/index.html"

# Contenu du fichier .htaccess
HTACCESS_CONTENT="RewriteEngine On

RewriteBase /

# Empêcher l'accès au fichier autoconfig.bash
<Files "autoconfig_vm.bash">
    Order Allow,Deny
    Deny from all
</Files>

# Ignorer les fichiers et répertoires existants
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rediriger toutes les autres requêtes vers index.php
RewriteRule ^(.*)$ index.php [QSA,L]"

# Ajouter le texte au fichier de configuration Apache
if ! grep -q "<Directory /var/www/html>" "$APACHE_CONF"; then
    echo -e "\n$APACHE_CONFIG_BLOCK" >> "$APACHE_CONF"
    echo "Configuration Apache mise à jour."
else
    echo "Le bloc de configuration existe déjà dans $APACHE_CONF."
fi

# Activer le module rewrite d'Apache
if /usr/sbin/a2enmod rewrite; then
    echo "Module rewrite activé."
else
    echo "Échec de l'activation du module rewrite." >&2
    exit 1
fi

# Redémarrer le service Apache
if systemctl restart apache2; then
    echo "Service Apache redémarré avec succès."
else
    echo "Échec du redémarrage du service Apache." >&2
    exit 1
fi

# Créer ou écrire le fichier .htaccess
if echo -e "$HTACCESS_CONTENT" > "$HTACCESS_FILE"; then
    echo "Fichier .htaccess créé ou mis à jour à l'emplacement $HTACCESS_FILE."
else
    echo "Échec de la création du fichier .htaccess." >&2
    exit 1
fi

# Supprimer le fichier index.html s'il existe
if [ -f "$INDEX_FILE" ]; then
    rm "$INDEX_FILE"
    echo "Fichier index.html supprimé."
else
    echo "Le fichier index est correctement configuré."
fi