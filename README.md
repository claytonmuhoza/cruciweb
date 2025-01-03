# Cruciweb
## Configuration automatique à l'aide d'un script
On copier tous les fichiers dans le dossier `/var/www/html`
La configuration de la vm se fait automatiquement en executant en tant que root le script bash
se trouvant dans le fichier `/var/www/html/autoconfig_vm.bash`

```
bash /var/www/html/autoconfig_vm.bash
```

## configuration de la base de donnée
La configuration des informations de la base de donnée sont dans le fichier `var/www/html/Config/Database.php`. Si le nom d'utilisateur, le serveur et le nom
de la base de donnée sont bien configurer les tables seront créés automatique et l'utilisateur `admin` sera c
## identifiant par défault de l'utilisateur admin
Les identifiants de connexion pour l'utilisateur `admin` sont:

`username:` admin

`password:` madrillet 

L'application est pret à être utiliser.