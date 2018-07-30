# NearbyShops
## Laravel + VueJs + MongoDb App


## Usage

* Clone the repo and cd into it
```sh
git clone https://github.com/gb1994/nearbyShops.git
```
* Install all the dependencies using composer
```sh
$ composer install
```
* rename or copy ``.env.example`` to ``.env``
```sh
$ cp .env.example .env
```
* Generate a new application key  
```sh 
$ php artisan key:generate
```
#### Set mongodb connection in your `.env` file
``` 
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=shops
DB_USERNAME=
DB_PASSWORD=
```
### Use one of the following two steps
>## 1. Refresh your database if it's mounted  

```sh 
$ php artisan migrate
```
* Then insert `2dsphere` index in the shops collection
```sh 
$ use shops
$ db.shops.createIndex({location:"2dsphere"})
```
* Check the TTL index as well if its generated
```sh 
$ db.disliked_shops.getIndexes()
```
* If not 
```sh 
$ db.disliked_shops.createIndex({"created_at":1},{expireAfterSeconds:7200})
```
>## 2. Use the zip attached 
* Extract the file 
* Loads data
```sh 
$ mongorestore --db shops shops/
```
## Finally
```sh 
$ npm install
$ npm run dev
$ php artisan serve
```
> If you face some issues with the assets
```sh 
$ php -S localhost:8000 -t public
```
* It should work now
```sh
127.0.0.1:8000
```
> If you face some issues with location in firefox
1. go to about:config
1. search for `geo.wifi.uri`
1. change it to `https://location.services.mozilla.com/v1/geolocate?key=test`
