# Инструкции по развертыванию

## Требования к серверу

- PHP 8.3+
- PostgreSQL 15+
- Composer
- Node.js 18+
- Nginx/Apache

## Установка

### 1. Клонирование репозитория
```bash
git clone https://github.com/your-username/laravel-product-catalog-api.git
cd laravel-product-catalog-api
```

### 2. Установка зависимостей
```bash
composer install --optimize-autoloader --no-dev
npm ci && npm run build
```

### 3. Настройка окружения
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Настройка базы данных
```bash
# Создание базы данных PostgreSQL
createdb catalog_api

# Настройка .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=catalog_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Миграции и сидеры
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 6. Настройка прав доступа
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Очистка кэша
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan products:clear-cache
```

## Настройка веб-сервера

### Nginx конфигурация
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/laravel-product-catalog-api/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Мониторинг и обслуживание

### Логи
```bash
# Просмотр логов Laravel
php artisan pail

# Логи Nginx
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log
```

### Очистка кэша
```bash
# Очистка всех кэшей
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan products:clear-cache
```

### Обновление приложения
```bash
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Безопасность

### SSL сертификат
```bash
# Установка Let's Encrypt
certbot --nginx -d your-domain.com
```

### Firewall
```bash
# Настройка UFW
ufw allow 22
ufw allow 80
ufw allow 443
ufw enable
```

### Резервное копирование
```bash
# Создание бэкапа базы данных
pg_dump catalog_api > backup_$(date +%Y%m%d_%H%M%S).sql

# Восстановление из бэкапа
psql catalog_api < backup_file.sql
```

## Мониторинг производительности

### Проверка статуса
```bash
# Статус сервисов
systemctl status nginx
systemctl status php8.3-fpm
systemctl status postgresql

# Использование ресурсов
htop
df -h
free -h
```

### Оптимизация
```bash
# Очистка кэша фильтров при изменении данных
php artisan products:clear-cache

# Оптимизация автозагрузки
composer dump-autoload --optimize
```
