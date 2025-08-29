# Technical-assessment for HRTech

### Note sur les commits
Le projet a été développé d’une traite (environ 3h) puis pushé en une fois.  
Dans un vrai contexte projet, j’adopterai un workflow Git plus structuré (commits incrémentaux, messages clairs, branches par fonctionnalité).

### Étape 6 – Sécurisation des données

En parcourant le projet, voici les principaux points qui me semblent poser problème côté sécurité et que j’améliorerais dans le cadre d'une vraie application en production :

1. **Clé sensible dans le repo**  
   - Le fichier credentials/credentials.json contient une clé API en clair.  
   - Si on pousse ce repo sur GitHub, la clé est exposée publiquement.  
   - À mon sens ce genre de fichier ne devrait pas être versionné. Je le mettrais dans le gitignore et je garderais un exemple avec des valeurs factices.

2. **Accès direct aux fichiers PHP**  
   - Aujourd’hui n’importe qui peut taper directement l’URL d’un ajax.php ou edit.php.  
   - C’est gênant parce qu’on peut forcer un id ou un clientId et récupérer les données d’un autre client.  
   - Une première mesure serait de vérifier systématiquement côté PHP que les données affichées correspondent bien au client connecté (ce que j’ai déjà fait dans les filtres), et idéalement de passer par un contrôleur central plutôt que des fichiers accessibles directement.

3. **Cookie peu fiable**  
   - Le client actif est déterminé uniquement par un cookie client_id.  
   - Comme le cookie est modifiable depuis le navigateur, ça ne peut pas suffire en vrai.  
   - Dans un vrai projet, je remplacerais ça par un système de session utilisateur après authentification (login/password) pour éviter que quelqu’un puisse changer son cookie et voir d’autres données.

4. **Validation des entrées**  
   - Je filtre déjà certaines valeurs (setClient limité à clienta, clientb, clientc), mais il reste des endroits où on pourrait être plus strict (par ex. s’assurer que id est bien un entier).  
   - Ça éviterait d’éventuelles injections ou des accès non prévus.

5. **Autres améliorations possibles**  
   - En production, je désactiverais l’affichage des erreurs PHP (pour ne pas exposer les chemins internes).  
   - J’utiliserais HTTPS et des cookies sécurisés (httponly, secure).  
   - Je limiterais aussi les permissions du serveur web pour que les fichiers `data/` ne soient pas directement exposés.

## Améliorations générales

- **Architecture** : structurer le code façon MVC et utiliser un moteur de templates (Twig) pour séparer logique et affichage.  
- **Données** : remplacer les JSON par une base MySQL avec PDO et requêtes sécurisées.  
- **Migration Symfony** : tirer parti des routes, entités, services et du .env pour la config sensible.  
- **Sécurité** : mettre en place une vraie authentification (sessions, rôles), protéger les formulaires (CSRF) et les cookies (`httponly`, `secure`).  
- **Qualité** : ajouter des tests unitaires (PHPUnit) et un CI simple pour vérifier les commits.
