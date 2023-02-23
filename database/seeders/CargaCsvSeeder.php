<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConsultaCp;

class CargaCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConsultaCp::truncate();

        $getcsvData = fopen(public_path('codescsv/cp.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($getcsvData, 2000, ';')) !== false) {
            if (!$transRow) {
                ConsultaCp::create([
                    'd_codigo'          => $data['0'],
                    'd_asenta'          => $data['1'],
                    'd_tipo_asenta'     => $data['2'],
                    'd_mnpio'           => $data['3'],
                    'd_estado'          => $data['4'],
                    'd_ciudad'          => $data['5'],
                    'd_cp'              => $data['6'],
                    'c_estado'          => $data['7'],
                    'c_oficina'         => $data['8'],
                    'c_cp'              => $data['9'],
                    'c_tipo_asenta'     => $data['10'],
                    'c_mnpio'           => $data['11'],
                    'id_asenta_cpcons'  => $data['12'],
                    'd_zona'            => $data['13'],
                    'c_cve_ciudad'      => $data['14'],
                ]);
            }
            $transRow = false;
        }
        fclose($getcsvData);
    }
}


//Itdyaingenieria