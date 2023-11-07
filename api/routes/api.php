<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(
    ['as' => 'api.', 'namespace' => 'App\Http\Controllers\Api'],
    static function (): void {
        Route::group(
            [
                'prefix' => 'product',
                'as' => 'product.',
            ],
            static function (): void {
                Route::resource('/', 'ProductsController')->only('store');
                Route::get('/{article_path?}', 'ProductsController@show')->name('show');
                Route::put('/{article_path?}', 'ProductsController@update');
                Route::delete('/{article_path?}', 'ProductsController@destroy');
            }
        );
    }
);
