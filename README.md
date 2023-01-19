# Laravel Installer
Laravel installer package

### Installation
```bash
composer require arispati/laravel-installer
```

### Publish Asset
```bash
php artisan vendor:publish --provider="Arispati\LaravelInstaller\LaravelInstallerProvider" --tag=public --force
```

### Publish Config
```bash
php artisan vendor:publish --provider="Arispati\LaravelInstaller\LaravelInstallerProvider" --tag="config"
```