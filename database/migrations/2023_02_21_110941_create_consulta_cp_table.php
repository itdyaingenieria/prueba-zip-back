<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulta_cp', function (Blueprint $table) {
            $table->id();
            $table->string("d_codigo")->comment('Código Postal asentamiento');
            $table->string("d_asenta")->comment('Nombre asentamiento');
            $table->string("d_tipo_asenta")->comment('Tipo de asentamiento (Catálogo SEPOMEX)');
            $table->string("d_mnpio")->comment('Nombre Municipio (INEGI, Marzo 2013)');
            $table->string("d_estado")->comment('Nombre Entidad (INEGI, Marzo 2013)');
            $table->string("d_ciudad")->nullable()->comment('Nombre Ciudad (Catálogo SEPOMEX)');

            $table->unsignedInteger("d_cp")->comment('Código Postal de la Administración Postal que reparte al asentamiento');
            $table->unsignedInteger("c_estado")->comment('Clave Entidad (INEGI, Marzo 2013)');
            $table->unsignedInteger("c_oficina")->comment('Código Postal de la Administración Postal que reparte al asentamiento');

            $table->string("c_cp")->nullable()->default(null)->comment('Campo Vacio');
            $table->unsignedInteger("c_tipo_asenta")->comment('Clave Tipo de asentamiento (Catálogo SEPOMEX)');
            $table->unsignedInteger("c_mnpio")->comment('Clave Municipio (INEGI, Marzo 2013)');
            $table->unsignedInteger("id_asenta_cpcons")->comment('Identificador único del asentamiento (nivel municipal)');

            $table->string("d_zona")->nullable()->comment('Zona en la que se ubica el asentamiento (Urbano/Rural)');
            $table->string("c_cve_ciudad")->nullable()->comment('Clave Ciudad (Catálogo SEPOMEX)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consulta_cp');
    }
};
