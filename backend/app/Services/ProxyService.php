<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Class ProxyService
 *
 * @package Service
 */
class ProxyService {

  /**
   *  TODO: нужно будет написать скрипт который по крону раз в час забирает свежие актуальный прокси-IP
   *        с этого сайта https://hidemy.name/ru/proxy-list/, перед записью в
   *        базу в идеале чтобы IP ещё проверялся на работоспособность
   *
   * @return Collection
   */
  public static function getProxyIpCollection(): Collection {
    $proxy_ip_list = [
      '172.67.70.88:80',
    ];

    return collect($proxy_ip_list);
  }
}
