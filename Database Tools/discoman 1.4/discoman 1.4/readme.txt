DiscoMan

version 1.4 du 22/05/05

Installation automatique :

1°) Dézippez le fichier discoman 1.4.zip

2°) Depuis votre navigateur, lancez le fichier <chemin d'accès>/install/install.php

Etape 1 :

SERVER : localhost (si vous installez le script en local) ou l'adresse de votre serveur mysql

BASE : le nom de la base à créer

NOM : root ou votre user

PASSWORD : néant ou votre mot de passe

3°) Validez pour accéder à l'étape 2

4°) Lancez l'application ! Vous pouvez effacer le répertoire install

Installation manuelle :

Si l'installation automatique a échouée :

1°) Dézippez le fichier discoman 1.4.zip

2°) Modifiez le fichier link.inc.php en insérant entre les guillemets le nom de votre serveur, le nom de votre base de données, votre nom d'utilisateur et votre mot de passe.

Si vous êtes en local, par exemple :

$serveur = "localhost";
$database = "discoman"; (le nom de votre base de données)
$username = "root";
$password = "";

3°) Puis, grâce à PhpMyadmin, exécutez le fichier discoman.sql

Cela aura pour effet de créer les tables.

4°) Rendez-vous à (si vous travaillez en local) localhost/<nom_de_votre_répertoire>/index.php

ou si vous travaillez sur un serveur distant : <nom_de_votre_domaine>/<nom_de_votre_répertoire>/index.php

Bonne utilisation !

Plus d'infos : http://www.the-mirror-of-dreams.com rubrique "scripts php"

