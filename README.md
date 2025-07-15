<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
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
