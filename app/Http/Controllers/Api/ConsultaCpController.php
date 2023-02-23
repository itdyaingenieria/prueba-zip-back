<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Repository\ConsultaCpRepository;


class ConsultaCpController extends Controller
{

    private $consultacpRepository;

    public function __construct(ConsultaCpRepository $consultacpRepository)
    {
        $this->consultacpRepository = $consultacpRepository;
    }


    public function getZipCodes($zipCode)
    {

        return $this->consultacpRepository->getZipCodes($zipCode);
    }
}
