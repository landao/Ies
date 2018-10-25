<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'HomeController@index')->name('home');

Route::get('pypark/pushDataConfig','ParkController@pushData');
Route::get('pypark/batchProcessConfig','ParkController@batchProcessConfig');
Route::get('pypark/gatewayconfig','ParkController@gatewayLogConfig');
Route::get('pypark/clustermaster/{masterIp}','ParkController@getClusterMasterPage');
Route::get('pypark/setupDataLogger','ParkController@setupDataLogger');
Route::get('pypark/dataLoggerSetting','ParkController@dataLoggerSetting');

Route::get('pycomp/ipython','PyCompController@ipython');


Route::resource('passports','PassportController');

Route::resource('pypark','ParkController');


Route::resource('pycomp','PyCompController');

Route::post('pypark/deploy', [
    'uses' => 'ParkController@deploy'
  ]);

Route::post('pypark/streamConfigSend', [
    'uses' => 'ParkController@streamConfigSend'
  ]);

Route::post('pypark/batchProcessSend', [
    'uses' => 'ParkController@batchProcessSend'
  ]);

Route::post('pypark/execgatewayconfig', [
    'uses' => 'ParkController@execGatewayConfig'
]);


Route::post('pycomp/puttofile', [
    'uses' => 'PyCompController@runPyCode'
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



