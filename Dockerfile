FROM php:8.3-fpm

# Update & Install Build Tools & X11 Libs (Untuk glcontext)
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
    libx11-dev \
    libxcursor-dev \
    libxinerama-dev \
    libxi-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set Environment agar CMake tidak rewel soal versi
ENV CMAKE_POLICY_VERSION_MINIMUM=3.5

# Instal PHP Extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Instal Torch Versi CPU (Wajib!)
RUN pip3 install torch torchvision --index-url https://download.pytorch.org/whl/cpu --break-system-packages

# Copy requirements
COPY TripoSR/requirements.txt /tmp/requirements.txt

# Instal requirements dengan tambahan flags agar kompatibel
RUN pip3 install -r /tmp/requirements.txt --break-system-packages

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www

# Upload size config
RUN echo "upload_max_filesize = 50M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini