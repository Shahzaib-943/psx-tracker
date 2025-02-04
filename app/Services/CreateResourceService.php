<?php

namespace App\Services;

class CreateResourceService
{

    public function create(String $model, array $vaidatedData)
    {
        return $model::create($vaidatedData);
    }

}
