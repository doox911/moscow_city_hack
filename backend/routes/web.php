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
  $proxy_ip_list = ProxyService::getProxyIpCollection();

  $url = config('services.fns.url') . "search?q=any&filter=active+region77&key=" . config('services.fns.secret');

  $http_client_params = [
    'timeout' => 30.0,
    'cookie' => true,
    'verify' => false,
    'request.options' => [],
  ];

  if ($proxy_ip_list->isNotEmpty()) {
    //$http_client_params['request.options']['proxy'] = 'tcp://@' . $proxy_ip_list[array_rand($proxy_ip_list->toArray())];
  }

  $this->client = new Client($http_client_params);

  $url = 'https://ident.me/';
  $res_json = $this->client->request('GET', $url, [
    'proxy' => 'http://85.12.221.147:80',
    'allow_redirects' => true,
    'timeout' => 2000,
  ]);
  dd($res_json->getBody()->getContents());



  $proxy = '23.227.38.25:80';
  $proxyauth = '';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);         // URL for CURL call
  curl_setopt($ch, CURLOPT_PROXY, $proxy);     // PROXY details with port
  //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);   // Use if proxy have username and password
  curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); // If expected to call with specific PROXY type
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  // If url has redirects then go to the final redirected URL.
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);  // Do not outputting it out directly on screen.
  curl_setopt($ch, CURLOPT_HEADER, 1);   // If you want Header information of response else make 0
  $curl_scraped_page = curl_exec($ch);
  curl_close($ch);

  dd($curl_scraped_page);
});
