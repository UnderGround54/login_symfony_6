# login_symfony_6
login and registration symfony 6

prérequis
version php > 8
composer

1 - pour lancer cette application, il faut configurer le fichier .env
DATABASE_URL="mysql://utilisateur:mot_de_passe@127.0.0.1:3306/nom_de_base?serverVersion=8&charset=utf8mb4"
APP_ENV=dev pour le developpement
APP_ENV=prod pour la production

2 - Istallation des paquets
$ composer install

3 - Creation de la base de données
$ symfony console doctrine:dadabase:create

4- Migration vers base de données
$ symfony console doctrine:migrations:migrate

5- lancer avec 
$ symfony server:start
