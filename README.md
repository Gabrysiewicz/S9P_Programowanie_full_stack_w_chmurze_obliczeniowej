<h1 align="center"> Programowanie full-stack w chmurze obliczeniowej </h1>

# 1. Install Kubernetes via minikube (WSL2 Ubuntu 24.04)
```
curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
sudo install minikube-linux-amd64 /usr/local/bin/minikube
```

# 2. Install kubectl
```
curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
chmod +x kubectl
sudo mv kubectl /usr/local/bin/

kubectl version --client
```

# 3. Start minikube
```
minikube start
```
## 3b. Verify kubectl context
```
kubectl config current-context
```

## 3c. Verify KUBECONFIG
```
export KUBECONFIG=~/.kube/config
echo $KUBECONFIG
```
## 3d. Test connection to API server
```
kubectl cluster-info
```

# 4. Install Dashboard Add-on
```
kubectl apply -f https://raw.githubusercontent.com/kubernetes/dashboard/v2.7.0/aio/deploy/recommended.yaml
```

# 5. Cheatsheet
```
1. Pod (Pod)
Opis: Najmniejsza jednostka w Kubernetes, reprezentująca jeden lub więcej kontenerów działających w tej samej przestrzeni sieciowej i współdzielących zasoby (np. woluminy).

2. Deployment
Opis: Abstrakcja służąca do deklaratywnego zarządzania Podami. Deployment zapewnia skalowalność, odtwarzanie po awarii oraz możliwość aktualizacji aplikacji (rolling updates).

3. Service
Opis: Umożliwia stały dostęp do Podów, niezależnie od ich dynamicznych adresów IP. Oferuje mechanizmy load-balancingu.

4. Ingress
Opis: Odpowiada za routing HTTP/S do usług w klastrze. Wymaga kontrolera Ingress (np. NGINX, Traefik).

5. ConfigMap i Secret
Opis:
  ConfigMap: Przechowuje konfigurację aplikacji w postaci tekstowej (np. pliki konfiguracyjne, zmienne środowiskowe).
  Secret: Podobny do ConfigMap, ale służy do przechowywania danych wrażliwych (np. hasła, klucze API).

6. PersistentVolume (PV) i PersistentVolumeClaim (PVC)
Opis: Mechanizmy służące do zarządzania trwałym przechowywaniem danych w Kubernetes.
  PV: Reprezentuje zasób pamięci dostępny w klastrze.
  PVC: Żądanie pamięci przez aplikację.

7. Namespace
Opis: Logiczna przestrzeń izolacji w klastrze Kubernetes, używana do zarządzania zasobami różnych projektów lub środowisk.
```

# 6. App Deployment
## Base config for Nginx + Laravel + MySQL via docker-compose

Dockerfile
```
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the project files to the working directory
COPY . .

# Install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Change ownership and permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 *

CMD ["php-fpm"]
```

docker-compose.yaml:
```
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: my-app:latest
    volumes:
      - .:/var/www/html
    networks:
      - swarm
    expose:
      - "9000"

  mysql:
    image: 'mysql/mysql-server:8.0'
    environment:
      MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
      MYSQL_DATABASE: '${DB_DATABASE}'
      MYSQL_USER: '${DB_USERNAME}'
      MYSQL_PASSWORD: '${DB_PASSWORD}'
    volumes:
      - database:/var/lib/mysql
    networks:
      - swarm
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-p${DB_PASSWORD}"]
      retries: 3
      timeout: 5s

  nginx:
    image: nginx:latest
    ports:
      - "8080:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf:ro
      - .:/var/www/html
    depends_on:
      - app
    networks:
      - swarm

networks:
  swarm:
    driver: bridge

volumes:
  database:
    driver: local

```

.env:
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:7Ztxjtz5SzrGEy03YlZ+6BanmFlxPdyTOOBRJQz0pl4=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

nginx.conf:
```
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;  # Note: PHP-FPM runs on port 9000 by default
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Serve static files directly
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires max;
        log_not_found off;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Build for kubernetes
laravel-deployment:
```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: laravel-app
spec:
  replicas: 1
  selector:
    matchLabels:
      app: laravel
  template:
    metadata:
      labels:
        app: laravel
    spec:
      containers:
      - name: laravel
        image: hehexa/laravel-app:latest
        ports:
        - containerPort: 9000
        env:
        - name: DB_CONNECTION
          value: mysql
        - name: DB_HOST
          value: mysql
        - name: DB_PORT
          value: "3306"
        - name: DB_DATABASE
          value: laravel
        - name: DB_USERNAME
          value: root
        - name: DB_PASSWORD
          valueFrom:
            secretKeyRef:
              name: mysql-secret
              key: password
        volumeMounts:
        - name: storage-volume
          mountPath: /var/www/html/storage/app/public
      volumes:
      - name: storage-volume
        persistentVolumeClaim:
          claimName: storage-pvc
---
apiVersion: v1
kind: Service
metadata:
  name: laravel-app
spec:
  ports:
  - port: 9000
  selector:
    app: laravel
---
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: storage-pvc
spec:
  accessModes:
    - ReadWriteOnce
  resources:
    requests:
      storage: 1Gi
```

mysql-deployment:
```
apiVersion: v1
kind: Secret
metadata:
  name: mysql-secret
type: Opaque
data:
  password: YWRtaW4= # "admin" in base64
---
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: mysql-pvc
spec:
  accessModes:
    - ReadWriteOnce
  resources:
    requests:
      storage: 1Gi
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: mysql
spec:
  selector:
    matchLabels:
      app: mysql
  strategy:
    type: Recreate
  template:
    metadata:
      labels:
        app: mysql
    spec:
      containers:
      - name: mysql
        image: mysql/mysql-server:8.0
        env:
        - name: MYSQL_ROOT_PASSWORD
          valueFrom:
            secretKeyRef:
              name: mysql-secret
              key: password
        - name: MYSQL_DATABASE
          value: laravel
        - name: MYSQL_ROOT_HOST
          value: "%" # Allow connections from any host
        ports:
        - containerPort: 3306
        volumeMounts:
        - name: mysql-storage
          mountPath: /var/lib/mysql
      volumes:
      - name: mysql-storage
        persistentVolumeClaim:
          claimName: mysql-pvc
---
apiVersion: v1
kind: Service
metadata:
  name: mysql
spec:
  ports:
  - port: 3306
  selector:
    app: mysql
```

nginx-deployment:
```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx
spec:
  replicas: 1
  selector:
    matchLabels:
      app: nginx
  template:
    metadata:
      labels:
        app: nginx
    spec:
      containers:
      - name: nginx
        image: nginx:latest
        ports:
        - containerPort: 80
        volumeMounts:
        - name: nginx-config
          mountPath: /etc/nginx/conf.d/default.conf
          subPath: nginx.conf
      volumes:
      - name: nginx-config
        configMap:
          name: nginx-config
---
apiVersion: v1
kind: Service
metadata:
  name: nginx
spec:
  type: LoadBalancer
  ports:
  - port: 80
    targetPort: 80
  selector:
    app: nginx
---
apiVersion: v1
kind: ConfigMap
metadata:
  name: nginx-config
data:
  nginx.conf: |
    server {
        listen 80;
        server_name localhost;
        root /var/www/html/public;
        index index.php index.html;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass laravel-app:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }
        # Handle storage files
        location /storage {
            alias /var/www/html/storage/app/public;
            try_files $uri $uri/ =404;
            expires max;
            access_log off;
        }
    }
```

To Run:
```
kubectl apply -f laravel-deployment.yaml
kubectl apply -f mysql-deployment.yaml
kubectl apply -f nginx-deployment.yaml

kubectl exec -it deployment/laravel-app -- php artisan migrate
kubectl port-forward service/nginx 8080:80

http://localhost:8080/
```
