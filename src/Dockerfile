# ใช้อิมเมจ PHP-FPM เวอร์ชัน 7.4 เป็นฐาน
FROM php:8.1-fpm

# ตั้งค่า locale ให้เป็น C.UTF-8
ENV LANG=C.UTF-8
ENV LC_ALL=C.UTF-8

# ติดตั้ง dependencies ที่จำเป็นสำหรับการรัน Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip curl pkg-config \
    libzip-dev libicu-dev libonig-dev libxml2-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
 && rm -rf /var/lib/apt/lists/*

# ติดตั้ง PHP extensions ที่ Laravel ต้องการ
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    gd pdo_mysql mbstring exif pcntl bcmath intl zip opcache

# ติดตั้ง Composer โดยคัดลอกมาจากอิมเมจ Composer (multi-stage build)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# กำหนด working directory ภายใน container
WORKDIR /var/www/html

# คัดลอกไฟล์ composer.json และ composer.lock
COPY composer.json composer.lock ./


# คัดลอกไฟล์ทั้งหมดของโปรเจกต์เข้ามาใน container
COPY . .

# ย้ายหรือคัดลอกไฟล์ตัวอย่าง .env ให้เป็น .env
# (ตรวจสอบให้แน่ใจว่าไฟล์ตัวอย่างในโปรเจกต์ของคุณชื่อ .env.sample)
RUN cp .env.example .env

# ติดตั้ง dependencies ด้วย Composer (ไม่รันสคริปต์)
RUN composer install

# [ตัวเลือก] หากต้องการ optimize autoload สามารถรันคำสั่งนี้ได้
RUN composer dump-autoload -o

RUN php artisan key:generate

# เปิดพอร์ต 9000 สำหรับ PHP-FPM
EXPOSE 9000

# กำหนดคำสั่งเริ่มต้นสำหรับรัน PHP-FPM
CMD ["php-fpm"]
