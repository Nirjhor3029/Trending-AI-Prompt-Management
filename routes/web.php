<?php

use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::post('/users/{user}/generate-token', [UsersController::class, 'generateToken'])->name('users.generate-token');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Prompt
    Route::delete('prompts/destroy', 'PromptController@massDestroy')->name('prompts.massDestroy');
    Route::post('prompts/media', 'PromptController@storeMedia')->name('prompts.storeMedia');
    Route::post('prompts/ckmedia', 'PromptController@storeCKEditorImages')->name('prompts.storeCKEditorImages');
    Route::resource('prompts', 'PromptController');

    // Ai Platform
    Route::delete('ai-platforms/destroy', 'AiPlatformController@massDestroy')->name('ai-platforms.massDestroy');
    Route::resource('ai-platforms', 'AiPlatformController');

    // Page
    Route::delete('pages/destroy', 'PageController@massDestroy')->name('pages.massDestroy');
    Route::resource('pages', 'PageController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
