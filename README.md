# This package has been abandoned and is no longer maintained.

Caffeinated GitHub
==================
[![Laravel 5.1](https://img.shields.io/badge/Laravel-5.1-orange.svg?style=flat-square)](http://laravel.com)
[![Laravel 5.2](https://img.shields.io/badge/Laravel-5.2-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-caffeinated/github-blue.svg?style=flat-square)](https://github.com/caffeinated/github)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

This is a Laravel 5 GitHub API Wrapper package that is heavily influenced by both KNP Labs [php-github-api](https://github.com/KnpLabs/php-github-api) client and Graham Campbell's [Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub) package. It's essentially a merger of the two packages born out of the need for more control over the code base for some personal projects.

Notable Differences
---------------
- Utilizes GuzzleHttp v5.0
- Certification verification is disabled
- Only supports token authentication for the time being
- Has very few API implementations

Documentation
-------------
You will find user friendly and updated documentation in the wiki here: [Caffeinated Github Wiki](https://github.com/caffeinated/github/wiki)

Quick Installation
------------------
Begin by installing the package through Composer.

```
composer require caffeinated/github=~2.0
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider
```php
Caffeinated\Github\GithubServiceProvider::class,
```

#### Facade
```php
'Github' => Caffeinated\Github\Facades\Github::class,
```

And that's it! With your coffee in reach, start calling on the GitHub API!
