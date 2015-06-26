Caffeinated GitHub
==================

This is a Laravel 5.0 GitHub API Wrapper package that is heavily influenced by both KNP Labs [php-github-api](https://github.com/KnpLabs/php-github-api) client and Graham Campbell's [Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub) package. It's essentially a merger of the two packages born out of the need for more control over the code base for some personal projects.

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
Begin by installing the package through Composer. Depending on what version of Laravel you are using (5.0 or 5.1), you'll want to pull in the `~1.0` or `~2.0` release, respectively:

#### Laravel 5.0.x
```
composer require caffeinated/github=~1.0
```

#### Laravel 5.1.x
```
composer require caffeinated/github=~2.0
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Laravel 5.0.x
##### Service Provider
```php
'Caffeinated\Github\GithubServiceProvider',
```

##### Facade
```php
'Github' => 'Caffeinated\Github\Facades\Github',
```

#### Laravel 5.1.x
##### Service Provider
```php
Caffeinated\Github\GithubServiceProvider::class,
```

##### Facade
```php
'Github' => Caffeinated\Github\Facades\Github::class,
```

And that's it! With your coffee in reach, start calling on the GitHub API!
