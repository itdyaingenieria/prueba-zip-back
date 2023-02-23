<?php

namespace App\Repository;


use App\Interfaces\ConsultaCpRepositoryInterface;
use App\Http\Traits\ZipCodeTraits;
use App\Models\ConsultaCp;
use stdClass;
use Illuminate\Support\Facades\Cache;

class ConsultaCpRepository implements ConsultaCpRepositoryInterface
{
    use ZipCodeTraits;

    /**
     * Constructor.
     */
    public function __construct()
    {
        //en cosntruccion
    }

    // Save a Vacant in Template of CRM
    public function getZipCodes($data)
    {
        if (Cache::has('zipcodes')) {
            $data = Cache::get('zipcodes');
        } else {
            $data = ConsultaCp::where("d_codigo", $data)->get();
            Cache::put('zipcodes', $data);
        }


        if (isset($data)) {

            $info = $data->first();
            $dEstado = $this->eliminarAcentos($info->d_estado);
            // Federal entity
            $federalEntity = new stdClass();
            $federalEntity->key  = $info->c_estado;
            $federalEntity->name = mb_strtoupper($dEstado);
            $federalEntity->code = !$info->c_cp ? null : $info->c_cp;

            $municipality = new stdClass();
            $municipality->key  = $info->c_mnpio;

            $municipality->name = mb_strtoupper($this->eliminarAcentos($info->d_mnpio));

            $settlements = collect();

            foreach ($data as $line) {

                $settlementType = new stdClass();
                $settlementType->name = $line->d_tipo_asenta;

                $settle = new stdClass();
                $settle->key                = $line->id_asenta_cpcons;
                $settle->name               = mb_strtoupper($this->eliminarAcentos($line->d_asenta));
                $settle->zone_type          = mb_strtoupper($line->d_zona);
                $settle->settlement_type    = $settlementType;

                $settlements->push($settle);
            }

            // Armamos la respuesta del json
            $result = new stdClass();
            $result->zip_code       = (string)$info->d_codigo;

            $result->locality       = mb_strtoupper($this->eliminarAcentos($info->d_ciudad));
            $result->federal_entity = $federalEntity;

            $result->settlements    = $settlements;
            $result->municipality   = $municipality;

            return response()->json($result);
        }

        return response()->json($data);
    }
}
