<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('official_numbers', function (Blueprint $table) {
            $table->id();
            
            $table->string('official_number')->unique();
            $table->dateTime('assignment_date');
            $table->string('treasury_office_number', 50)->nullable();
            $table->dateTime('treasury_date')->nullable();
            
            // NUEVO CAMPO: TIPO DE PREDIO
            $table->enum('property_type', ['vivienda', 'comercial'])->default('vivienda');

            $table->string('owner_name');
            $table->string('curp_rfc')->nullable();
            
            $table->string('street_name');
            $table->string('ext_number');
            $table->string('int_number')->nullable();
            $table->string('suburb');
            $table->string('city');
            
            // Referencias
            $table->string('colindancia_norte', 150)->nullable();
            $table->string('colindancia_sur', 150)->nullable();
            $table->string('colindancia_este', 150)->nullable();
            $table->string('colindancia_oeste', 150)->nullable();
            $table->string('referencia_cercana', 255)->nullable();

            $table->decimal('front_measurement', 8, 2);
            $table->decimal('depth_measurement', 8, 2);
            $table->decimal('area_sqm', 10, 2);
            
            $table->longText('croquis_base64')->nullable();
            
            // Documentos
            $table->string('doc_escrituras')->nullable();
            $table->string('doc_constancia')->nullable();
            $table->string('doc_ine')->nullable();
            $table->string('doc_ine_reverso')->nullable();
            $table->string('doc_predial')->nullable();

            $table->unsignedBigInteger('assigned_by_user_id')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_numbers');
    }
};