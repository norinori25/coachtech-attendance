# coachtech勤怠管理アプリ

## 環境構築

### Dockerビルド

1. git clone <git@github.com>:norinori25/coachtech-attendance.git
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build

---

## Laravel環境構築

1. docker-compose exec php bash
2. composer install
3. cp .env.example .env
4. .env（例）

APP_NAME=AttendanceApp
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=<http://localhost>

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS="<noreply@example.com>"
MAIL_FROM_NAME="Attendance App"

1. アプリケーションキー作成  
php artisan key:generate

2. マイグレーションの実行  
php artisan migrate

3. シーディングの実行  
php artisan db:seed

---

## テスト環境構築

1. .env.testingを作成

DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=demo_test  
DB_USERNAME=root  
DB_PASSWORD=root  

1. テスト用DB作成(MySQLコンテナ内)

docker exec -it coachtech-attendance-mysql-1 bash  
mysql -u root -p  
パスワード：root  

CREATE DATABASE demo_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

1. テスト用マイグレーション  
php artisan migrate --env=testing

---

## テスト実行

1. docker-compose exec php bash
2. php artisan test

---

## 使用技術(実行環境)

・言語 PHP 8.x  
・フレームワーク Laravel 10  
・データベース MySQL 8.0.26  
・Webサーバー Nginx 1.21.1  
・パッケージ管理 Composer  
・メール確認ツール MailHog  
・コンテナ管理 Docker / docker-compose  

---

## ER図

（ER図画像をここに貼る）

---

## URL

・開発環境： <http://localhost/>  
・phpMyAdmin: <http://localhost:8080/>  
・MailHog: <http://localhost:8025/>  
