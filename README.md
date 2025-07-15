
<p align="center">
  <img src="https://i.imgur.com/EHJkjOV.png" width="400" alt="Laravel KML County Splitter Logo">
</p>

# Laravel KML County Splitter

**Laravel + Livewire component** to upload and process KML files, extract placemarks, and export results per county into KML & CSV files.

## ✨ Features

- Upload single or multiple `.kml` or `.xml` files
- Select counties (`VS`, `GL`, `BZ`, `BR`) to filter placemarks
- Auto-generates downloadable KML and CSV files per selected county
- Supports extended data in placemarks (SchemaData + Data)

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
