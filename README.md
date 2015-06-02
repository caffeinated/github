Caffeinated GitHub
==================

This is a Laravel 5.0 GitHub API Wrapper package that is heavily influenced by both KNP Labs [php-github-api](https://github.com/KnpLabs/php-github-api) client and Graham Campbell's [Laravel-GitHub](https://github.com/GrahamCampbell/Laravel-GitHub) package. It's essentially a merger of the two packages born out of the need for more control over the code base for some personal projects.

If you are looking to implement and make use of the GitHub API within your own application (Laravel or not), I highly recommend you skip over this package and go take a look at the two mentioned above.

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
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself:

```
composer require caffeinated/github
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider
```
'Caffeinated\Github\GithubServiceProvider',
```

#### Facade
```
'Github' => 'Caffeinated\Github\Facades\Github',
```

And that's it! With your coffee in reach, start calling on the GitHub API!
