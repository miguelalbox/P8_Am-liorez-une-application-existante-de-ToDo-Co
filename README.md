# P8_Ameliorez-une-application-existante-de-ToDo-Co

## Apporter des modifications au projet

- Telecharger le repo github de projet dans votre envirenement de travail grâce a la commande ***git clone https://github.com/miguelalbox/P8_Ameliorez-une-application-existante-de-ToDo-Co.git***


- Une fois vous l’avez, il vous faut l’ouvrir avec votre éditeur de code préféré. 


- Ouvré le terminal integré, positioné vous a la raciné de projet, et lancé la commande ***composer update*** et apres ***composer install*** pour instaler tout les **bundles** necesaire au projet. 
  - les information de bundles qui von etre installé se trouve dans le fichier **composer.json** qui se trouve a la racine.

  
- Une fois tout cella est installé, on connecte la base de données, pour cella on crée un fichier a la racine appelé **.env.local** puis on copie le code qui se trouve dans le **.env**, on modifie la connexion a la base de donnes, dans mon cas est mysql. Voici un exemple de connexion : ***DATABASE_URL="mysql://root:@127.0.0.1:3306/p8todo?serverVersion=5.7.34"***


- Pour créer la base de donné on lance la commande ***php bin/console doctrine:database:create***


- Une fois crée on lance les **fixtures** pour avoir de donnes de test avec la commande ***php bin/console doctrine:fixtures:load*** apres quelque secondes la base de donnes sera remplis de donées de test.


- A cette stade on est prêt a lancer le projet grace a la commande ***symfony serve -d*** , sur le terminal aparait une **url**, on clic puis le projet va être ouvert sur le navigateur, a nous de créer une compte pour commencer a naviguer.


- Pour vérifier que le projet est correct on va lancer le **teste unitaire**, pour cella on va dans le terminal et on lance la commande ***vendor/bin/phpunit --coverage-html public/test-coverage*** puis après quelque seconde on voit sur le terminal si tout le test est bien passé. Sinon il affichera ou se trouve l’erreur dans la partie test et on aurait plous de visibilité pour résoudre le bug.
