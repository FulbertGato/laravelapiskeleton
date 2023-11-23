# Étape 1: Base image avec PHP 8.1 et PHP-FPM
FROM php:8.1-fpm

# Mettre à jour les packages et installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers source de l'application dans le conteneur
COPY . /var/www/html

# Copier, exécuter et supprimer le script de déploiement
COPY deploy-app.sh /var/www/html/deploy-app.sh
RUN chmod +x /var/www/html/deploy-app.sh && \
    /var/www/html/deploy-app.sh && \
    rm /var/www/html/deploy-app.sh

# Configurer Nginx pour Laravel
RUN echo 'server {\
    listen 80;\
    index index.php index.html;\
    error_log  /var/log/nginx/error.log;\
    access_log /var/log/nginx/access.log;\
    root /var/www/html/public;\
    location / {\
        try_files $uri $uri/ /index.php?$query_string;\
    }\
    location ~ \.php$ {\
        try_files $uri =404;\
        fastcgi_split_path_info ^(.+\.php)(/.+)$;\
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;\
        fastcgi_index index.php;\
        include fastcgi_params;\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\
        fastcgi_param PATH_INFO $fastcgi_path_info;\
    }\
    location ~ /\.ht {\
        deny all;\
    }\
}' > /etc/nginx/sites-available/default

# Exposer le port 80
EXPOSE 80

# Lancer Nginx et PHP-FPM
CMD ["nginx", "-g", "daemon off;"]
