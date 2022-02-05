# Création d'un web service exposant une API
Développement de la vitrine de téléphones mobiles de l’entreprise BileMo exposant un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations

En esposant Une API Rest avec le framework symfony. 

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


4 Créer la base de donnée si cette base n'hesiste pas encore 
- bin/console doctrine:database:create

 Mettre a jour les entites en base de donnée
- bin/console doctrine:schema:update -f

5  Lancer les fixtures pour avoir des données de test en base
- bin/console doctrine:fixtures:load

6 Demarrer Votre serveur avec la commande ci-dessous:
- php -S localhost:8000 -t public
- sur votre navigateur ecrire l'url :http://localhost:8000/



