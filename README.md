# API pour la gestion d'articles

Cette API en PHP permet de gérer des articles en fonction de trois rôles : Modérateur, Publicateur et Anonyme.
## Fonctionnalités

L'API propose les fonctionnalités suivantes :

### Publier un article

Pour publier un article, il faut envoyer une requête POST avec les informations de l'article à publier, 
c'est à dire la phrase. Le reste des informations sera récupéré automatiquement. Cette action est réservée aux utilisateurs avec le rôle Publicateur.

### Modifier un article

Pour modifier un article existant, il faut envoyer une requête PUT avec les informations de l'article à modifier ainsi que l'identifiant 
de l'article à modifier. Cette action est réservée aux utilisateurs avec le rôle Modérateur.

### Supprimer un article

Pour supprimer un article existant, il faut envoyer une requête DELETE avec l'identifiant de l'article à supprimer. 
Cette action est réservée aux utilisateurs avec le rôle Modérateur.

### Afficher un article

Pour afficher un article existant, il faut envoyer une requête GET avec l'identifiant de l'article à afficher. Cette action est accessible à tous les utilisateurs.

### Afficher tous les articles

Pour afficher tous les articles, il faut envoyer une requête GET sans spécifier d'identifiant d'article. Cette action est accessible à tous les utilisateurs. 
Pour afficher un article spécifique, il faut envoyer une requête avec l'identifiant de l'article à afficher. 

## Utilisateurs

Les utilisateurs sont définis en dur dans l'API.


## Gestion des erreurs

L'API utilise des constantes pour identifier les erreurs possibles. En cas d'erreur, une fonction get_erreur est disponible pour fournir 
des informations détaillées sur l'erreur rencontrée.
