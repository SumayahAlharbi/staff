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
    return view('index');
});

Auth::routes();
Route::get('/signin', 'Auth\MsGraphLoginController@signin')->name('mslogin');
Route::get('/callback', 'Auth\MsGraphLoginController@callback');
Route::get('/signout', 'Auth\MsGraphLoginController@signout');

Route::get('/home', 'HomeController@index')->name('home');
Route::post('AttendanceSheet', 'AttendanceSheetController@store')->name('attendancesheet.store');
