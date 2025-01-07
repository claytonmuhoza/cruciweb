# Cruciweb
## Lancement en developpement
pour lancer l'application assurer vous de configurer correctement le fichier `Config/Database.php`
lancer l'application avec la commande
```
php -S localhost:4000
```
l'application va démarrer sur le port 4000
## Configuration automatique à l'aide d'un script pour le deploiement sur un serveur appacher
On copier tous les fichiers dans le dossier `/var/www/html`
La configuration de la vm se fait automatiquement en exécutant en tant que root le script bash se trouvant dans le fichier 
`/var/www/html/autoconfig_vm.bash`

```
bash /var/www/html/autoconfig_vm.bash
```

## configuration de la base de donnée
La configuration des informations de la base de données sont dans le fichier `var/www/html/Config/Database.php`. Si le nom d'utilisateur, le serveur et le nom de la base de donnée sont bien configurés, les tables seront créées automatiquement et l'utilisateur `admin` sera créé automatiquement.

## identifiant par défault de l'utilisateur admin
Les identifiants de connexion pour l'utilisateur `admin` sont:

`username:` admin

`password:` madrillet 

L'application est pret à être utiliser.