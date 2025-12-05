# Architecture MVC - Logements

## Vue d'ensemble

L'application utilise le pattern MVC (Model-View-Controller) pour gérer les pages de recherche et de gestion des logements.

## Structure

```
php/
├── logements.php (Page principale - Dispatcher)
├── controllers/
│   └── LogementController.php (Logique métier)
├── models/
│   └── LogementModel.php (Requêtes base de données)
└── views/
    ├── recherche.php (Vue: Recherche de logements)
    └── mesannonces.php (Vue: Mes annonces)
```

## Composants

### 1. Model (`LogementModel.php`)
Gère toutes les interactions avec la base de données :
- `getApprovedLogements()` - Récupère les logements approuvés
- `getUserLogements($userId)` - Récupère les logements d'un utilisateur
- `deleteLogement($logementId)` - Supprime un logement
- `updateLogementStatus($logementId, $status)` - Change le statut d'un logement
- `getLogementById($logementId)` - Récupère un logement par ID

### 2. Controller (`LogementController.php`)
Orchestre la logique métier :
- `getSearchLogements()` - Retourne les logements pour la recherche
- `getUserLogements($userId)` - Retourne les logements de l'utilisateur
- `handleDelete()` - Traite les suppressions de logements
- `render($view, $data)` - Affiche une vue

### 3. Views
#### `recherche.php`
- Affiche les logements approuvés avec filtres de recherche avancés
- Contient la barre de recherche et les filtres
- Bouton de suppression visible uniquement pour les admins

#### `mesannonces.php`
- Affiche les logements de l'utilisateur connecté
- Montre le statut de chaque annonce (Approved, Waiting, etc.)
- Bouton de suppression toujours visible

### 4. Page Principale (`logements.php`)
- Initialise Model et Controller
- Gère le paramètre `view` (recherche ou mesannonces)
- Traite les suppressions de logements
- Affiche les messages de succès
- Inclut la vue appropriée

## Utilisation

### Accéder à la page de recherche
```
logements.php?view=recherche
```

### Accéder à mes annonces
```
logements.php?view=mesannonces
```

## Flux de données

```
logements.php (Dispatcher)
    ↓
LogementController (Logique)
    ↓
LogementModel (Données)
    ↓
Database (MySQL)
    ↓
views/recherche.php ou views/mesannonces.php (Affichage)
```

## Avantages de cette architecture

- ✅ **Séparation des préoccupations** : Chaque composant a une responsabilité unique
- ✅ **Réutilisabilité** : Le modèle peut être utilisé par d'autres contrôleurs
- ✅ **Maintenabilité** : Facile de modifier la logique ou l'affichage indépendamment
- ✅ **Testabilité** : Chaque composant peut être testé isolément
- ✅ **DRY** : Élimine la duplication de code entre mesannonces.php et recherche.php
