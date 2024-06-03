# Base de données
## Configuration
Pour configurer la base de données, il faut créer un fichier `.env` à la racine du projet et y ajouter les informations suivantes:
```
DATABASE_URL="postgresql://nytodev:nytodev@localhost:5432/nytodev?serverVersion=16&charset=utf8"
```
Se connecter au container de la base de données :
```bash
docker exec -it nytodev-database-1 /bin/bash
````
Se connecter à la base de données :
```bash
psql -U app
```
Créer un utilisateur :
```
CREATE USER nytodev WITH PASSWORD 'nytodev';
```
Créer une base de données :
```
CREATE DATABASE nytodev;
```
Donner les droits à l'utilisateur sur la base de données :
```
GRANT ALL PRIVILEGES ON DATABASE nytodev TO nytodev;
```
Ou
```
ALTER DATABASE nytodev OWNER TO nytodev;
```