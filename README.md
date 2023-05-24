# Error Symfony service container

This repository for issue Symfony: https://github.com/symfony/symfony/issues/50417

## Reproduce

Clone:
```
git clone https://github.com/Myks92/symfony-service-container.git
```

Install:
```
composer install
```

Run Debug container:
```
php bin/console debug:container
```

Result:
```
 ---------------------------------------------------------------------------- ---------------------------------------------------------------------------------------- 
  Service ID                                                                   Class name                                                                              
 ---------------------------------------------------------------------------- ---------------------------------------------------------------------------------------- 
  App\Auth\Command                                                             App\Auth\Command 
```

  `App\Auth\Command` - is folder!
