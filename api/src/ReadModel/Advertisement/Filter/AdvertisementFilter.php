<?php

declare(strict_types=1);

namespace App\ReadModel\Advertisement\Filter;

class AdvertisementFilter
{
    public ?int $id = null;
    public ?string $userFio = null;
    public ?string $title = null;
    public ?string $country = null;
    public ?string $city = null;
    public ?string $district = null;
    public ?string $status = null;
}
