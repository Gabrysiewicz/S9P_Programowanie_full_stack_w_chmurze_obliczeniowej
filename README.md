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

# 5. App Deployment
