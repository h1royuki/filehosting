<p align="center">
  <img src="https://i.imgur.com/oe2QOXL.png">
</p>


## Uppuru

Simple SPA anonymous filehosting.

### Used technologies

#### Backend
* Slim framework
* SphinxSearch
* MySQL
* GetID3

#### Frontend
* Vue.js
* Bootstrap
* Vue-Aplayer
* Vue-Dplayer
* Vue-Moment

### Requirements
* PHP >= 7.1
* SphinxSearch
* MySQL
* Composer
* npm

### Installation
* Clone repository `git clone https://github.com/h1royuki/filehosting.git`
* Configure `config/nginx.conf` and copy to `/etc/nginx/sites-enabled/` (or your config dir)
* Restart nginx `service nginx restart`
* Configure `config/sphinx.conf` and run Sphinx `sudo searchd --config sphinx.conf --console`
* Install backend dependencies `composer install`
* Install frontend dependencies `npm install`
* Configure `config/.env`
* Import `config/filehosting.sql` to MySQL database
* Build frontend `npm run build`

### Contributors
Thanks [rikka0612](https://github.com/rikka0612) for design!
