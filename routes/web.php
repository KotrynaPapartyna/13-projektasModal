<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('articles')->group(function () {

    Route::get('','ArticleController@index')->name('article.index');
    Route::get('create', 'ArticleController@create')->name('article.create');

    Route::post('store', 'ArticleController@store')->name('article.store');
    Route::post('storeAjax', 'ArticleController@storeAjax')->name('article.storeAjax');

    Route::get('edit/{article}', 'ArticleController@edit')->name('article.edit');
    Route::get('editAjax/{article}', 'ArticleController@editAjax')->name('article.editAjax');

    Route::post('update/{article}', 'ArticleController@update')->name('article.update');
    Route::post('updateAjax/{article}', 'ArticleController@updateAjax')->name('article.updateAjax');

    Route::post('delete/{article}', 'ArticleController@destroy' )->name('article.destroy');
    Route::post('deleteAjax/{article}', 'ArticleController@destroyAjax' )->name('article.destroyAjax');

    Route::get('show/{article}', 'ArticleController@show')->name('article.show');
    Route::get('showAjax/{article}', 'ArticleController@showAjax')->name('article.showAjax');

    Route::get('searchAjax', 'ArticleController@searchAjax') ->name('article.searchAjax');
    Route::get('indexAjax', 'ArticleController@indexAjax') ->name('article.indexAjax');
    Route::get('filterAjax', 'ArticleController@filterAjax') ->name('article.filterAjax');


    // pazymetu panaikinimas
    Route::post('destroySelected', 'ArticleController@destroySelected')->name('article.destroySelected');
});

Route::prefix('types')->group(function () {

    Route::get('','TypeController@index')->name('type.index');
    Route::get('create', 'TypeController@create')->name('type.create');

    Route::post('store', 'TypeController@store')->name('type.store');
    Route::post('storeAjax', 'TypeController@storeAjax')->name('type.storeAjax');

    Route::get('edit/{type}', 'TypeController@edit')->name('type.edit');
    Route::get('editAjax/{type}', 'TypeController@editAjax')->name('type.editAjax');

    Route::post('update/{type}', 'TypeController@update')->name('type.update');
    Route::post('updateAjax/{type}', 'TypeController@updateAjax')->name('type.updateAjax');

    Route::post('delete/{type}', 'TypeController@destroy' )->name('type.destroy');
    Route::post('deleteAjax/{type}', 'TypeController@destroyAjax' )->name('type.destroyAjax');

    Route::get('show/{type}', 'TypeController@show')->name('type.show');
    Route::get('showAjax/{type}', 'TypeController@showAjax')->name('type.showAjax');


    Route::get('searchAjax', 'TypeController@searchAjax') ->name('type.searchAjax');
    Route::get('indexAjax', 'TypeController@indexAjax') ->name('type.indexAjax');
    Route::get('filterAjax', 'TypeController@filterAjax') ->name('type.filterAjax');

    // pazymetu panaikinimas
    Route::post('destroySelected', 'TypeController@destroySelected')->name('type.destroySelected');

});
