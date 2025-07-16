
<p align="center">
  <img src="https://i.imgur.com/EHJkjOV.png" width="400" alt="Laravel KML County Splitter Logo">
</p>

<p align="center">
  A Laravel + Livewire application that allows users to upload KML files and automatically extract, filter and generate KML/CSV files per selected county.
</p>

<p align="center">
  <img src="https://i.imgur.com/bmrJ0A7.png" width="400" alt="Laravel Livewire KML Splitter Logo">
</p>

[![Hitsukaya](https://img.shields.io/badge/Hitsukaya-red)](https://hitsukaya.com)
[![PageSpeed Score](https://img.shields.io/badge/PageSpeed-100%25-brightgreen)](https://pagespeed.web.dev/)
[![License](https://img.shields.io/github/license/Hitsukaya/laravel-kml-county-splitter)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-12-red)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3-blueviolet)](https://livewire.laravel.com/)


✍️ Author: Hitsukaya 
🔗 https://hitsukaya.com

# Laravel KML County Splitter

**Laravel + Livewire component** to upload and process KML files, extract placemarks, and export results per county into KML & CSV files.

## ✨ Features

- ✅ Upload single or multiple `.kml` / `.xml` files
- ✅ Select counties  
- ✅ Automatically detect placemarks related to selected counties
- ✅ Generates downloadable KML and CSV per county

🧪 Tech Stack
- ✅ Laravel 12
- ✅ Livewire 3
- ✅ Tailwind CSS
- ✅ Alpine.js
- ✅ PHP 8.4+
- ✅ QGIS / KML / CSV data processing

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

## 🌐 Demo Online
[https://kml-splitter-kml-csv.hitsukaya.com](https://kml-splitter-kml-csv.hitsukaya.com)

## Report Issues
Please use the [GitHub Issues](https://github.com/Hitsukaya/laravel-kml-county-splitter/issues) to report bugs or request features.

