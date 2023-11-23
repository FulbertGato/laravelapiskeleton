#!/bin/bash

# Arrêter le script en cas d'erreur
set -e


# Installer/Actualiser les dépendances Composer
composer install --no-dev --optimize-autoloader

# Mettre à jour les variables d'environnement
# Vous pouvez ajouter ici des commandes pour gérer les variables d'environnement

# Exécuter les migrations de base de données
# Assurez-vous que les informations de connexion à la base de données sont correctes
php artisan migrate --force

# Vider le cache de l'application
php artisan cache:clear

# Vider et régénérer le cache de configuration
php artisan config:cache

# Vider et régénérer le cache des routes
php artisan route:cache


echo "Déploiement terminé avec succès !"
