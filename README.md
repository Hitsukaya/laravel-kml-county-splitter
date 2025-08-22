<p align="center">
  <img src="[public/images/hitsukaya-red-ball.png](https://imgur.com/a/larave-livewire-qgis-hitsukaya-2LO5kYZ)" width="400" alt="Hitsukaya Logo">
</p>

<p align="center">
<a href="https://hitsukaya.com"><img src="https://img.shields.io/badge/Hitsukaya-RED-ff0000?style=for-the-badge&logo=laravel" alt="Hitsukaya Red"></a>
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel KML County Splitter

Un proiect Laravel Livewire pentru conversia și manipularea fișierelor KML, CSV și GeoJSON.  
Ideal pentru integrare cu QGIS și alte instrumente GIS.

---

## Funcționalități

- Conversie KML ↔ CSV ↔ GeoJSON  
- Încărcare multiplă de fișiere KML/CSV  
- Procesare rapidă a datelor GIS  
- Interfață Livewire modernă  
- Ușor de integrat cu QGIS sau alte aplicații GIS  

---

## Instalare

1. Clonează repository-ul:

```bash
git clone https://github.com/Hitsukaya/laravel-kml-county-splitter.git
cd laravel-kml-county-splitter

composer install
npm install
npm run dev

cp .env.example .env
php artisan key:generate

php artisan serve
