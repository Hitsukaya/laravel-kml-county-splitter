
<p align="center">
  <img src="https://i.imgur.com/EHJkjOV.png" width="400" alt="Laravel KML County Splitter Logo">
</p>

<p align="center">
  <img src="https://i.imgur.com/bmrJ0A7.png" width="400" alt="Laravel Livewire KML Splitter Logo">
</p>

<p align="center">
  A Laravel + Livewire application that allows users to upload KML files and automatically extract, filter and generate KML/CSV files per selected county.
</p>

# Laravel KML County Splitter

**Laravel + Livewire component** to upload and process KML files, extract placemarks, and export results per county into KML & CSV files.

## ✨ Features

- ✅ Upload single or multiple `.kml` / `.xml` files
- ✅ Select counties  
- ✅ Automatically detect placemarks related to selected counties
- ✅ Generates downloadable KML and CSV per county

## 🚀 Installation

1. Clone the repo:
  
  ```
  git clone https://github.com/Hitsukaya/laravel-kml-county-splitter.git
  cd laravel-kml-county-splitter
  ```
  
2. Install Laravel dependencies:
  

```
composer install
npm install && npm run dev
php artisan migrate
php artisan serve
```

3. Make sure `storage/public` is linked:
  

```
php artisan storage:link
```
