
# Slam Service Filament Starterkit

> Easily start new projects using Slam Service's starterkit.

Usually, many of us are confused about where to start a new project. Because there are too many packages that need to be installed, that's why we made this starterpack to make it easier for us and for you to start a new project.

## What's Inside?

- [TALL Stack](https://tallstack.dev/)
  - [Tailwind CSS](https://tailwindcss.com/)
  - [Alpine JS](https://alpinejs.dev/)
  - [Laravel](https://laravel.com/)
  - [Livewire](https://laravel-livewire.com/)
- [FilamentAdmin](https://filamentphp.com/)
  - Official Plugin
    - [MediaLibrary](https://filamentphp.com/docs/2.x/spatie-laravel-media-library-plugin/installation)
    - [Settings](https://filamentphp.com/docs/2.x/spatie-laravel-settings-plugin/installation)
    - [Tags](https://filamentphp.com/docs/2.x/spatie-laravel-tags-plugin/installation)
    - [Translatable](https://filamentphp.com/docs/2.x/spatie-laravel-translatable-plugin/installation)
  - Plugin
    - [Filament Shield](https://github.com/bezhanSalleh/filament-shield)
    - [Impersonate](https://github.com/stechstudio/filament-impersonate)
- [SlamService](https://github.com/slamservice)
  - Custom Function
  - Profile Page
  - Setting Page
  - User Resources
  - Log Activities

## Upgrade

```bash
php artisan optimize:clear
composer update
php artisan filament:upgrade
php artisan shield:upgrade
```

## Instalation

1. Generate new repository with this template:
   https://github.com/slamservice/filament-starterkit
2. Install the package via composer:
   ```bash
    composer install
    ```
3. Compile all CSS and JS:
    ```bash
    npm install && npm run dev
    ```
4. Create copy of `.env` and generate laravel key:
    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan storage:link
    ```
5. Configure your `.env` file:

    We've added some new keys to make it easier for you to configure your app.

    ```env

    FILAMENTADMIN_EMAIL="@gmail.com"
    ```
6. Generate default permission and run migration:
    ```bash
    php artisan shield:install --fresh
    ```

    That command will flush all your table, and run some migration. After that you will be asked some questions, and generate new `super_admin` user.

    If you get the question :

    ```
    Shield Resource already exists. Overwrite?
    ````
    Please, just answer `no` because the command will overwrite the existing file.
7. Congratulation, you can access your application on `/admin` route. 🔥

## Usage

Now you can use the features of the TALL Stack and FilamentAdmin.

- [Configure Admin Panel](https://filamentphp.com/docs/2.x/admin/dashboard)
- [Create new resources](https://filamentphp.com/docs/2.x/admin/resources)
- [Create pages](https://filamentphp.com/docs/2.x/admin/resources#pages)
- and many more..

After creating the resource, please run the following command to create a new permission:

```bash
php artisan shield:generate
```

Next, you can read each documentation of the related packages. Thank you! 🥳

## Create Model, Migration, Policy
```bash
php artisan make:model SingularModelName -m
```



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Abela](https://github.com/abela-a)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
