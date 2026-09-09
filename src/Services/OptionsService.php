<?php

namespace App\Services;

use App\Repository\CustomizeRepository;

class OptionsService
{

    public function __construct(private CustomizeRepository $customise)
    {
    }

    public function getCustomize()
    {
        return $this->customise->findOneBy([]);
    }

}
