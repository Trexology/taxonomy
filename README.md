[![Latest Stable Version](https://poser.pugx.org/trexology/taxonomy/v/stable.svg)](https://packagist.org/packages/trexology/taxonomy)
[![Total Downloads](https://poser.pugx.org/trexology/taxonomy/downloads.svg)](https://packagist.org/packages/trexology/taxonomy)
[![License](https://poser.pugx.org/trexology/taxonomy/license.svg)](https://packagist.org/packages/trexology/taxonomy)

#Taxonomy

This package allows you to create vocabularies with terms in Laravel 4 and 5

## Installation

### Laravel 6

In your `composer.json` add:

	"require": {
		"trexology/taxonomy": "3.*"
	}

### Laravel 5

In your `composer.json` add:

	"require": {
		"trexology/taxonomy": "3.*"
	}

From the terminal run

    composer update

Then register the service provider and Facade by opening `app/config/app.php`

(Skip this step if you are on laravel 5.5)

```php
'Trexology\Taxonomy\TaxonomyServiceProvider',

'Taxonomy'        => 'Trexology\Taxonomy\Facades\TaxonomyFacade',
```

Then run the following artisant command to publish the config and migrations:

	php artisan vendor:publish

Then run the migrations:

	php artisan migrate

And finally in any of the Models where you want to use the Taxonomy functionality, add the following trait:

```php
<?php

class Car extends \Eloquent {
  use \Trexology\Taxonomy\TaxonomyTrait;
}
```

### Laravel 4

In your `composer.json` add:

	"require": {
		"trexology/taxonomy": "2.0.*"
	}

From the terminal run

    composer update

Then register the service provider and Facade by opening `app/config/app.php`

```php
'Trexology\Taxonomy\TaxonomyServiceProvider',

'Taxonomy' => 'Trexology\Taxonomy\Facades\TaxonomyFacade',
```

If you want you can publish the config files if you want to change them

    php artisan config:publish trexology/taxonomy

Perform the DB migrations to install the required tables

    php artisan migrate --package=trexology/taxonomy

And finally in any of the Models where you want to use the Taxonomy functionality, add the following trait:

```php
<?php

class Car extends \Eloquent {
  use \Trexology\Taxonomy\TaxonomyTrait;
}
```

## Usage

Creating a vocabulary:

```php
Taxonomy::createVocabulary('Cars');
```

Retrieving a Vocabulary:

```php
$vocabulary = Taxonomy::getVocabulary(1);             // Using ID
$vocabulary = Taxonomy::getVocabularyByName('Cars');  // Using Name
```

Deleting a Vocabulary:

```php
Taxonomy::deleteVocabulary(1);             // Using ID
Taxonomy::deleteVocabularyByName('Cars');  // Using Name
```

Adding a Term to a vocabulary:

```php
Taxonomy::createTerm($vocabulary->id, 'Audi');
```

You can also optionally specify a parent term and a weight for each, so you can group them together and keep them sorted:

```php
$german_cars = Taxonomy::createTerm($vocabulary->id, 'German Cars');
$italian_cars = Taxonomy::createTerm($vocabulary->id, 'Italian Cars');

$term_audi = Taxonomy::CreateTerm($vocabulary->id, 'Audi', $german_cars->id, 0);
$term_bmw  = Taxonomy::CreateTerm($vocabulary->id, 'BMW', $german_cars->id, 1);
$term_benz = Taxonomy::CreateTerm($vocabulary->id, 'Mercedes-Benz', $german_cars->id, 2);
$term_ferrari = Taxonomy::CreateTerm($vocabulary->id, 'Ferrari', $italian_cars->id, 0);
```

If terms contains additional params, it can be added in this manner:

```php
$params = [
	'shortname' => "GC",
	'type' => "Premium Cars",
];
$german_cars = Taxonomy::CreateTerm($vocabulary->id, 'BMW', $german_cars->id, 0, $params)
```

With the Car Model, I can create a new instance and assign it a term for the make it belongs to:

```php
$car = Car::create([
  'model' => 'A3',
]);

$car->addTerm($term_bmw->id);
$car->addTerm($term_benz->id);
$car->removeAllTerms();              // Remove all terms linked to this car

$car->addTerm($term_ferrari->id);
$car->removeTerm($term_ferrari-id);  // Remove a specific term

$car->addTerm($term_audi->id);

// Get all the terms from the vocabulary 'Cars' That
// are attached to this Car.
$terms = $car->getTermsByVocabularyName('Cars');
```

To retrieve all the cars that match a given term:

```php
$audis = Car::getAllByTermId($term_audi->id)->get();
```


Add these lines to your routes file, to access the UI for managing terms/vocabulary

```php
Route::resource('taxonomy', '\Trexology\Taxonomy\Controllers\TaxonomyController');
Route::post('postOrderTerms', '\Trexology\Taxonomy\Controllers\TaxonomyController@postOrderTerms');
Route::resource('terms', '\Trexology\Taxonomy\Controllers\TermsController');
```
Extend UI with your current admin theme by changing the configuration in `taxonomy.config.php`
