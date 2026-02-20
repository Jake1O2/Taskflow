# Configuration Inertia + React + Tailwind + ShadCN UI

## ✅ Ce qui a été fait

1. **Configuration Inertia Laravel**
   - Middleware `HandleInertiaRequests` créé
   - Configuration dans `bootstrap/app.php`
   - Layout Blade `resources/views/app.blade.php` créé

2. **Configuration React**
   - `resources/js/app.jsx` configuré avec Inertia
   - `vite.config.js` mis à jour pour React
   - Alias `@` configuré pour les imports

3. **Composants ShadCN UI**
   - Button, Input, Card, Label, Alert, Table, Badge, Avatar, Dialog, AlertDialog
   - Utilitaires (`cn` pour merge des classes)

4. **Pages React pour Teams**
   - `Teams/Index.jsx` - Liste des équipes
   - `Teams/Create.jsx` - Création d'équipe
   - `Teams/Show.jsx` - Détails d'une équipe
   - `Teams/Edit.jsx` - Modification d'équipe
   - `Layouts/AuthenticatedLayout.jsx` - Layout avec navigation

5. **TeamController mis à jour**
   - Utilise maintenant `Inertia::render()` au lieu de `view()`
   - Relations chargées pour les données React

## 📦 Installation des dépendances

### 1. Installer Inertia côté Laravel
```bash
composer require inertiajs/inertia-laravel
```

### 2. Publier le middleware Inertia
```bash
php artisan inertia:middleware
```

### 3. Installer les dépendances npm
```bash
npm install
```

### 4. Compiler les assets
```bash
npm run dev
# ou pour la production
npm run build
```

## 🔧 Configuration supplémentaire recommandée

### Ziggy pour les routes Laravel dans React (optionnel mais recommandé)
```bash
composer require tightenco/ziggy
npm install ziggy-js
```

Puis dans `HandleInertiaRequests.php`, ajouter :
```php
'ziggy' => fn () => [
    ...(new Ziggy)->toArray(),
    'location' => url()->current(),
],
```

Et dans `resources/js/app.jsx` :
```jsx
import route from 'ziggy-js';
window.route = route;
```

## 🎨 Notes sur le design

- Les composants utilisent Tailwind CSS avec les couleurs définies dans `resources/css/app.css`
- Les composants ShadCN UI sont stylisés pour correspondre au design existant
- Les animations et transitions sont préservées

## 🚀 Utilisation

Une fois les dépendances installées, les routes `/teams` utiliseront automatiquement les composants React au lieu des vues Blade.
