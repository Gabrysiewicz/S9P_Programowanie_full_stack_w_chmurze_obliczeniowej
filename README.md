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

Version 1: Works but without css
laravel-deployment:
```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: laravel-deployment
  labels:
    app: laravel
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
        envFrom:
        - secretRef:
            name: laravel-secret
---
apiVersion: v1
kind: Service
metadata:
  name: laravel
spec:
  selector:
    app: laravel
  ports:
    - protocol: TCP
      port: 9000
      targetPort: 9000
  type: ClusterIP

```

nginx-deployment:
```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx-deployment
  labels:
    app: nginx
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
        - name: nginx-snippets
          mountPath: /etc/nginx/snippets/fastcgi-php.conf
          subPath: fastcgi-php.conf
      volumes:
      - name: nginx-config
        configMap:
          name: nginx-config
      - name: nginx-snippets
        configMap:
          name: nginx-snippets
      - name: app-storage
        emptyDir: {}
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
      index index.php index.html index.htm;

      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }

      location ~ \.php$ {
          include snippets/fastcgi-php.conf;
          fastcgi_pass laravel:9000;
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
          include fastcgi_params;
      }

      location ~ /\.(ht|git|svn|env) {
          deny all;
      }

      # Serve static files
      location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|ttf|woff|woff2)$ {
          try_files $uri =404;
          expires max;
          access_log off;
      }
    }
---
apiVersion: v1
kind: ConfigMap
metadata:
  name: nginx-snippets
data:
  fastcgi-php.conf: |
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_index index.php;
    include fastcgi_params;

```

mysql-deployment:
```
apiVersion: apps/v1
kind: Deployment
metadata:
  name: mysql-deployment
  labels:
    app: mysql
spec:
  replicas: 1
  selector:
    matchLabels:
      app: mysql
  template:
    metadata:
      labels:
        app: mysql
    spec:
      containers:
      - name: mysql
        image: mysql:5.7
        env:
        - name: MYSQL_ROOT_PASSWORD
          value: password
        - name: MYSQL_DATABASE
          value: laravel
        - name: MYSQL_USER
          value: sail
        - name: MYSQL_PASSWORD
          value: password
        ports:
        - containerPort: 3306
        volumeMounts:
        - name: mysql-persistent-storage
          mountPath: /var/lib/mysql
      volumes:
      - name: mysql-persistent-storage
        persistentVolumeClaim:
          claimName: mysql-pv-claim
---
apiVersion: v1
kind: Service
metadata:
  name: mysql
spec:
  selector:
    app: mysql
  ports:
    - protocol: TCP
      port: 3306
      targetPort: 3306
  type: ClusterIP

```
