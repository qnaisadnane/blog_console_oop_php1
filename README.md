# 📝 BlogCMS - Console Edition

Système de Gestion de Contenu pour l'administration système

## 📖 Présentation du Projet

Dans le cadre de l'agence CodeCrafters Digital, nous avons développé cette version "Console-First" du CMS pour répondre aux besoins de sécurité et de performance de MediaPress International. L'outil permet une gestion granulaire du flux éditorial directement via le terminal, garantissant une absence totale de surface d'attaque web.

## 🛠️ Contexte Technique

- Langage : PHP 8.1+ (Code pur, sans framework)

- Architecture : Programmation Orientée Objet (POO)

- Interface : CLI (Command Line Interface)

- Performance : Optimisé pour 10 000+ articles

## 🚀 Fonctionnalités Clés

1. Gestion des Utilisateurs & Sécurité
- Authentification : Système de login sécurisé.

- Hachage : Utilisation de l'algorithme natif password_hash() (BCRYPT).

- Rôles : 4 niveaux d'accès (Visiteur, Auteur, Éditeur, Administrateur).

2. Moteur Éditorial
- Workflow : État de l'article évolutif (draft, published, archived).

- Multi-catégorisation : Possibilité d'assigner un article à plusieurs thématiques.

- Recherche : Moteur de recherche interne par mots-clés dans les titres et contenus.

3. Structure des Catégories
- Hiérarchie infinie : Gestion des catégories parentes et enfants.

- Validation : Empêchement strict des boucles récursives (une catégorie ne peut être son propre parent).

- Compteurs : Affichage dynamique du nombre d'articles par catégorie dans l'arborescence.

## Développé par : [QNAIS ADNANE]

Client : MediaPress International

Note : Ce projet respecte les principes de programmation propre (SOLID) et ne nécessite aucune extension PHP spécifique pour une portabilité maximale.



