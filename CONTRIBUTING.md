# Contribuer au projet Bot-Discord-Symfony-Model

Merci de votre intérêt pour contribuer à **Bot-Discord-Symfony-Model** ! Ce projet fournit une architecture préconçue pour intégrer Symfony avec l'API Discord et simplifier le développement de bots Discord. Ce document vous guidera dans le processus de contribution.

## Comment contribuer ?

### 1. Signalez un problème
Si vous rencontrez un bug ou avez une suggestion pour améliorer le projet :
1. Vérifiez d'abord dans les [issues existantes](https://github.com/maximemarc/Bot-Discord-Symfony-Model/issues) si le problème a déjà été signalé.
2. Si ce n'est pas le cas, ouvrez une nouvelle issue en fournissant :
   - Une description claire et concise.
   - Les étapes pour reproduire le problème (le cas échéant).
   - Toute suggestion ou idée pour résoudre le problème.

### 2. Proposez une fonctionnalité
Si vous souhaitez ajouter une nouvelle fonctionnalité :
1. Ouvrez une issue pour discuter de votre idée avec les mainteneurs du projet.
2. Décrivez en détail la fonctionnalité, son utilité et comment elle s'intègre dans le projet.

### 3. Contribuez avec du code
#### Pré-requis :
- Avoir une bonne compréhension de Symfony et de l'API Discord.
- Installer PHP et Composer sur votre machine.
- Forkez ce dépôt sur votre compte GitHub.

#### Étapes :
1. **Clonez le dépôt depuis votre fork :**
   ```bash
   git clone https://github.com/maximemarc/Bot-Discord-Symfony-Model.git
 ``
Installez les dépendances avec Composer :
 ```bash
composer install
 ```
Créez une branche pour vos modifications :
 ```bash
git checkout -b feature/nom-de-la-fonctionnalite
 ```
Développez et testez vos modifications :

    Assurez-vous que votre code respecte les normes de codage PHP.
    Testez votre fonctionnalité dans un environnement local.

Validez et poussez vos modifications :
bash

    git add .
    git commit -m "Ajout de [description de la fonctionnalité ou correction]"
    git push origin feature/nom-de-la-fonctionnalite

    Créez une Pull Request (PR) :
        Allez dans l'onglet Pull Requests du dépôt principal.
        Cliquez sur "New Pull Request".
        Décrivez brièvement vos modifications et mentionnez leur utilité.

Bonnes pratiques pour les PR :

    Fournir une description claire de vos modifications.
    Référencer les issues liées (s'il y en a) dans la description.
    Garder des changements concis et bien documentés.

4. Écrivez des tests

Les tests sont essentiels pour garantir la qualité du code. Si vous ajoutez une nouvelle fonctionnalité ou corrigez un bug, essayez d'ajouter des tests unitaires ou fonctionnels :

    Utilisez PHPUnit pour écrire vos tests.
    Placez vos tests dans le répertoire tests/.

5. Documentez vos changements

Si votre contribution nécessite des modifications ou des ajouts à la documentation :

    Mettez à jour le fichier README.md ou créez de nouveaux fichiers de documentation dans un répertoire docs/.

Code de conduite

En contribuant à ce projet, vous acceptez de respecter notre Code de Conduite.
Ressources utiles

    Documentation Symfony
    Documentation Discord API
    Normes de codage PHP PSR
    Guide GitHub pour les débutants

Merci pour votre contribution ! Ensemble, nous pouvons rendre ce projet encore meilleur.
