<?php

namespace App\Parsers;

use App\Abstractions\AbstractParser;
use App\Contracts\IParser;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

/**
 * @description Парсер сайта "Производство России"
 */
class ProductCenterParser extends AbstractParser implements IParser {

  /**
   * @inheritDoc
   */
  public function parse(): Collection {
    $companies = $this->searchCompanies();

    $inn_list = array_map(function ($company) {
      $c = $company->{"ЮЛ"} ?? $company->{"ИП"};

      return $c->{"ИНН"};
    }, $companies);

    return $this->searchGroupInfo($inn_list);
  }

  /**
   * @throws GuzzleException
   */
  public function searchCompanies() {
    $url = config('services.fns.url') . "search?q=any&filter=active+region77&key=" . config('services.fns.secret');

    $res_json = $this->client->request('GET', $url);

    return json_decode($res_json->getBody())->items;
  }

  /**
   * @throws GuzzleException
   */
  public function searchGroupInfo(array $inn): Collection {
    $url = config('services.fns.url') . "multinfo?req=" . implode(',', $inn) . "&key=" . config('services.fns.secret');

    $res_json = $this->client->request('GET', $url);

    return collect(json_decode($res_json->getBody())->items);
  }
}
