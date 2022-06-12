<?php

namespace App\Abstractions;

use App\Contracts\IParser;
use App\Services\ProxyService;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

abstract class AbstractParser implements IParser {

  /**
   * @var string
   */
  protected static string $url = '';

  /**
   * @var Client
   */
  protected Client $client;

  /**
   * Конструктор
   */
  public function __construct() {
    $proxy_ip_list = ProxyService::getProxyIpCollection();

    $http_client_params = [
      'timeout' => 900,
      'cookie' => true,
      'verify' => false,
      'request.options' => [],
    ];

    if ($proxy_ip_list->isNotEmpty()) {
      //$http_client_params['request.options']['proxy'] = 'http://' . $proxy_ip_list[array_rand($proxy_ip_list->toArray())];
    }

    $this->client = new Client($http_client_params);
  }

  abstract function parse(string $query = ''): Collection;
}
