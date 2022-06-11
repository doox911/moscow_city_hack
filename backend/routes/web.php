<?php

use App\Services\ProxyService;
use GuzzleHttp\Client;
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


Route::get('/test', function () {
  /*
  $proxy_ip_list = ProxyService::getProxyIpCollection();

  $url = config('services.fns.url') . "search?q=any&filter=active+region77&key=" . config('services.fns.secret');

  $http_client_params = [
    'timeout' => 30.0,
    'cookie' => true,
    'verify' => false,
    'request.options' => [

    ],
  ];

  if ($proxy_ip_list->isNotEmpty()) {
    //$http_client_params['request.options']['proxy'] = 'tcp://@' . $proxy_ip_list[array_rand($proxy_ip_list->toArray())];
  }

  $this->client = new Client($http_client_params);


  //$url = 'https://ident.me/';
  $res_json = $this->client->request('GET', $url, [
    'proxy' => 'http://103.145.76.44:80',
    'allow_redirects' => [
      'max'             => 10,        // allow at most 10 redirects.
      'strict'          => true,      // use "strict" RFC compliant redirects.
      'referer'         => true,      // add a Referer header
      'protocols'       => ['https', 'http'], // only allow https URLs
      'track_redirects' => true
    ],
    'timeout' => 2000,
  ]);
  dd($res_json->getBody()->getContents());
*/


  $url = 'https://ident.me/';
  $proxy = '45.142.73.221';
  $proxyPort = '46418';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  //proxy suport
  curl_setopt($ch, CURLOPT_PROXY, $proxy);
  curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
  curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'bMMAiubR:L6uQQL2H');
  curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
  curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
  //https
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/27.0.1453.94 Safari/537.36");
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 100);

  $output = curl_exec($ch);

  if (curl_exec($ch) === false) {
    echo 'Curl error: ' . curl_error($ch);
  } else {
    echo 'Operation completed without any errors';
  }

  echo $output;
});


Route::get('/test', function () {
  $url = 'https://ident.me/';
  $proxy = '103.145.76.44';
  $proxyPort = '80';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  //proxy suport
  curl_setopt($ch, CURLOPT_PROXY, $proxy);
  curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
  //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
  curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
  curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
  //https
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/27.0.1453.94 Safari/537.36");
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 100);

  $output = curl_exec($ch);

  if (curl_exec($ch) === false) {
    echo 'Curl error: ' . curl_error($ch);
  } else {
    echo 'Operation completed without any errors';
  }

  echo $output;
});