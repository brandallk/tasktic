
## About Clean-Slate-Laravel

Clean-Slate-Laravel is a modification of Laravel 5.5 that has been cleaned up and prepared for an ideal greenfield experience when starting a new Laravel project.

## Features

- Laravel's basic auth scaffold and routes are pre-installed. The routes are registered explicitly in routes/web.php for greater transparency. The User model is moved into a new app/Models/ directory; config and namespacing changes are in place to make this work.
- The 'Welcome' page route is named and uses a WelcomeController method rather than a closure.
- All the starter CSS and Bootstrap classes are stripped out of the Blade views in resources/views/, leaving only the bare placeholder content. Likewise, the starter styles in resources/assets/sass/ have been deleted. The sample Vue component loaded by resources/assets/js/app.js has been disabled.
- The package.json scripts are modified to NOT use an alias for node_modules/cross-env/dist/bin/cross-env.js, which has been a pain-point for myself and others.
- The webpack.mix.js file has been updated to implement version-hashing for cache-busting CSS and JS assets, and the default resources/views/layouts/app.blade.php layout template has been updated to use the versioned assets. An initial set of versioned assets is present, as the `npm run dev` script has already been run once.
- Default timezone is set to mountain standard time.

## How to Use Clean-Slate-Laravel for a Starter Project

- Create a project database.
- Create a local virtual host (if desired) with a custom local url for the project under development.
- Create a project directory.
(Note for Laragon users on Windows: The above steps can be done automatically via "Menu -> Quick create -> Laravel", which will also run a Composer install of Laravel. You will be asked to supply a project name. You will get a MySQL db named "project-name", an Nginx virtual host at http://project-name.dev, and a project directory at C:\laragon\www\project-name.)
- Clone Clean-Slate-Laravel into the project directory:
`git clone https://github.com/brandallk/clean-slate-laravel.git`
(Note: If Laravel has already been installed, e.g. via a Laragon quick-create, erase everything from the project directory before running git clone.)
- Copy the ".env.example" file and rename it ".env". Run `php artisan key:generate` (see [Laravel docs](https://laravel.com/docs/5.5/encryption#configuration)) to create a new secure APP_KEY. Change at minimum APP_NAME (=project-name) and APP_URL (=virtual-host-url if a virtual host was created). If you are not running Homestead, also update DB_DATABASE (=name-of-the-database), DB_USERNAME (=probably "root" for dev mode), and DB_PASSWORD (=probably blank for dev mode).
- Review the "scripts" in the package.json file, which have been modified to deal with a persistent issue some users encounter with aliasing the cross-env.js file. (To change these back to defaults, see the original [repo](https://github.com/laravel/laravel/blob/master/package.json.).)
- Run `npm install`
- Run `git init`
- Run `php artisan migrate`
- Update the timezone config in config/app.php if you don't want to default to mountain standard time. (See [http://php.net/manual/en/timezones.php](http://php.net/manual/en/timezones.php).)

## License
Clean-Slate-Laravel and the Laravel framework are open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
