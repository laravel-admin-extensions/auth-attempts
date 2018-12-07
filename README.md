Captcha and Login attempts for laravel-admin
======
Add captcha and login attempts for laravel-admin


### Screenshot



### Installation

```
composer require manzhouya/auth-attempts
```

### Configuration

In the extensions section of the config/admin.php file, add configurations
```
'extensions' => [
     'auth-attempts' => [
         // set to false if you want to disable this extension
         'enable' => true,
         
         // configuration
         'maxAttempts'  => 5,
         'decayMinutes' => 1,
    ]
]
```

In the resources/lang/en(example)/validation.php file, add configurations
```
'captcha'    => 'The :attribute is invalid.',
'attributes' => [
    'captcha' => 'captcha',
],
```

### Usage

Open your login page in your browser


### License

Licensed under [The MIT License (MIT)](LICENSE).

