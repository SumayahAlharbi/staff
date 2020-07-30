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


Auth::routes(['register' => false]);
Route::get('/signin', 'Auth\MsGraphLoginController@signin')->name('mslogin');
Route::get('/callback', 'Auth\MsGraphLoginController@callback');
Route::get('/signout', 'Auth\MsGraphLoginController@signout');

Route::group(['middleware' => 'auth'], function () {
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


Route::post('AttendanceSheet', 'AttendanceSheetController@store')->name('attendancesheet.store');
Route::get('/attendance', 'AttendanceSheetController@index')->name('attendance')->middleware('permission:view attendance sheet');
// Export Attendance Sheet
Route::post('export/', 'ExportController@attendancesheet')->name('attendancesheet.export')->middleware('permission:export');
// Show User Profile
Route::get('/profile/{id}', 'UserController@showUserProfile')->name('profile.show');

Route::group(['middleware' => ['role:admin']], function () {


Route::resource('permissions', 'PermissionController');
Route::resource('roles', 'RoleController');

//Users route
Route::resource('users', 'UserController');
Route::get('/userSearch', 'UserController@userSearch')->name('user.userSearch');
Route::post('users/addRole', '\App\Http\Controllers\UserController@addRole');
Route::get('users/removeRole/{role}/{user_id}', '\App\Http\Controllers\UserController@revokeRole');

//Groups Routes
Route::resource('group', 'GroupController');

    // assign and remove user from a group
    Route::post('users/addUserGroup', 'UserController@addUserGroup');
    Route::get('users/removeUserGroup/{user_id}/{group_id}', '\App\Http\Controllers\UserController@removeUserGroup');

    // Add Permission to a user
Route::post('users/addPermission', '\App\Http\Controllers\UserController@addPermission');
Route::get('users/removePermission/{permission}/{user_id}', '\App\Http\Controllers\UserController@revokePermission');

    //roles has permissions Routes

Route::post('roles/addPermission', '\App\Http\Controllers\RoleController@addPermission');
Route::get('roles/removePermission/{permission}/{role_id}', '\App\Http\Controllers\RoleController@revokePermission');

});
});
