# Création d'un web service exposant une API REST
Développement de la vitrine de téléphones mobiles de l’entreprise BileMo exposant un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations

Service exposant Une API Rest avec le framework symfony. 

# Requirements:
 - Apache 2.4

 - PHP 7.2

 - MySQL 5.7

 - Composer
  

# Steps :

1 Clonez le dépôt depuis Github.

2 Installez les dépendances du projet  
- composer install


3 N'oubliez pas de remplir le fichier .env de votre base de donnée comme ci dessous par exemple:
- DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7


4 Créer la base de donnée si cette base n'existe pas encore 
- bin/console doctrine:database:create

 Mettre a jour les entités en base de donnée
- bin/console doctrine:schema:update -f

5 Lancer les fixtures pour avoir des données de test en base
- bin/console doctrine:fixtures:load

6 Démarrer Votre serveur avec la commande ci-dessous :
- php -S localhost:8000 -t public
- sur votre navigateur écrire l'url :http://localhost:8000/

7 Pour consulter la documentation de l'API :
- l'url : http://localhost:8000/doc

- Vous pouvez aussi utiliser le logiciel Postman Pour tester L'API


- plus d'info pour installer le logiciel Postman en visitant ce lien : https://www.postman.com/downloads/

# Annexe :
- Lien d'analyse du code ci-dessous : 
- https://codeclimate.com/github/Elhadj75BAH/API-REST



