<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// User Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// User Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')
    ->name('register');

Route::post('register', 'Auth\RegisterController@register');

// User Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.request');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.email');

Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
    ->name('password.reset');

Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Welcome page
Route::get('/', 'WelcomeController@showWelcome')->name('welcome');

// About page
Route::get('/about', 'AboutController@showAbout')->name('about');

// Help page
Route::get('/help', 'HelpController@showHelp')->name('help');

// Contact page
Route::get('/contact', 'ContactController@showContact')->name('contact');

/**
 * Routes accessible only to a logged-in user
 */
Route::middleware(['auth'])->group(function () {

    // Home page
    Route::get('/home', 'HomeController@showHome')->name('home');

    // TaskList resources
    Route::get('/lists', 'ListController@index')->name('lists.index');

    Route::post('/lists', 'ListController@store')->name('lists.store');

    Route::patch('/lists/{list}', 'ListController@update')->name('lists.update');

    Route::get('/lists/{list}', 'ListController@show')->name('lists.show');

    Route::delete('/lists/{list}', 'ListController@destroy')->name('lists.destroy');

    Route::post('/lists/{list}/create-element', 'ListController@createListElement')
        ->name('lists.create.element');

    Route::get('/lists/{list}/priorities', 'ListController@priorities')
        ->name('lists.priorities');

    // Category resources
    Route::post('/categories', 'CategoryController@store')->name('categories.store');

    Route::patch('/categories/{category}', 'CategoryController@update')
        ->name('categories.update');

    Route::delete('/categories/{category}', 'CategoryController@destroy')
        ->name('categories.destroy');

    // Subcategory resources
    Route::post('/subcategories', 'SubcategoryController@store')->name('subcategories.store');

    Route::patch('/subcategories/{subcategory}', 'SubcategoryController@update')
        ->name('subcategories.update');

    Route::delete('/subcategories/{subcategory}', 'SubcategoryController@destroy')
        ->name('subcategories.destroy');

    // Task resources
    Route::post('/tasks', 'TaskController@store')->name('tasks.store');

    Route::patch('/tasks/{task}/details', 'TaskController@updateDetails')
        ->name('tasks.update.details');

    Route::patch('/tasks/{task}/status', 'TaskController@updateStatus')
        ->name('tasks.update.status');

    Route::patch('/tasks/{task}/priority', 'TaskController@updatePriority')
        ->name('tasks.update.priority');

    Route::delete('/tasks/{task}', 'TaskController@destroy')->name('tasks.destroy');

    Route::patch('/tasks/{task}/reposition', 'TaskController@reposition')
        ->name('tasks.reposition');

    // Task-item resources (DeadlineItems, DetailItems, LinkItems)
    Route::post('/items', 'ItemController@store')->name('items.store');

    Route::patch('/items/detail/{item}', 'ItemController@updateDetail')->name('items.update.detail');

    Route::patch('/items/link/{item}', 'ItemController@updateLink')->name('items.update.link');

    Route::delete('/items/deadline/{item}', 'ItemController@destroyDeadline')->name('items.destroy.deadine');

    Route::delete('/items/detail/{item}', 'ItemController@destroyDetail')->name('items.destroy.detail');

    Route::delete('/items/link/{item}', 'ItemController@destroyLink')->name('items.destroy.link');

});
