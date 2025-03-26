# Veterinarian Project

## 🐾 Présentation
Le **Veterinarian Project** est une application web destinée à optimiser la gestion des cliniques vétérinaires. Elle permet de gérer efficacement les dossiers des patients, les rendez-vous, la facturation et bien plus encore.

## ✨ Fonctionnalités
- **Dossiers des patients** : Conservez des informations détaillées sur chaque animal.
- **Gestion des rendez-vous** : Planifiez, mettez à jour et suivez facilement les consultations.
- **Facturation** : Gérez les paiements et la facturation de manière simplifiée.

## ⚙️ Installation
1. Clonez le dépôt :
   ```sh
   git clone https://github.com/Leo-Riche/RenduAPI
   ```
2. Accédez au dossier du projet :
   ```sh
   cd RenduAPI
   ```
3. Installez les dépendances requises :
   ```sh
   composer install
   ```

## 🚀 Lancement de l'application
1. Démarrez le serveur :
   ```sh
   symfony serve
   ```
2. Ouvrez votre navigateur et accédez à :
   ```
   http://localhost:8000
   ```

## 🔎 Fonctionnalités de recherche
L'application intègre un moteur de recherche avancé pour retrouver rapidement les dossiers des patients, les rendez-vous et la facturation.

### Exemples de requêtes :
- **Rechercher par plage de dates de rendez-vous** :
  ```
  https://127.0.0.1:8000/api/consultations?createdDate[after]=2000-01-01&createdDate[strictly_before]=2000-01-02
  ```
- **Rechercher une consultation spécifique par date** :
  ```
  https://127.0.0.1:8000/api/consultations?createdDate[after]=2025-03-21T00:00:00&createdDate[before]=2025-03-21T23:59:59
  ```
- **Filtrer par statut** :
  ```
  https://127.0.0.1:8000/api/consultations?statut=termine
  ```
- **Filtrer par statut de paiement** :
  ```
  https://127.0.0.1:8000/api/consultations?isPaid=1
  ```
  (true = 1 ; false = 0)

## 📬 Utilisation de l'API
Toutes les routes disponibles peuvent être testées via la collection Postman **"Veterinarian Symfony Project.postman_collection.json"** fournie à la racine du projet.

Importez-la dans Postman et commencez vos tests ! 🎉

## 👥 Équipe du projet
- **Raphaël Chiche**
- **Léo Riché**
