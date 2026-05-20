FROM php:8.3-fpm

# 1. Update & Install Build Tools (Dapur kompilasi C++)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm \
    build-essential \
    cmake \
    ninja-build \
    python3-dev \
    python3 \
    python3-pip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instal PHP Extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Instal Torch Versi CPU (Wajib agar tidak error cari CUDA)
RUN pip3 install torch torchvision --index-url https://download.pytorch.org/whl/cpu --break-system-packages

# 4. Copy requirements dari folder TripoSR
COPY TripoSR/requirements.txt /tmp/requirements.txt

# 5. Instal requirements (Wajib jalankan ini setelah torch)
RUN pip3 install -r /tmp/requirements.txt --break-system-packages

# 6. Composer & Upload Size
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
RUN echo "upload_max_filesize = 50M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini