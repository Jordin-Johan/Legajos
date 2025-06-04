<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seccions = [
            'SECCION 01 - Información Personal y Familiar',
            'SECCION 02 - Incorporacón (Selección, vinculación, Inducción, Periodo de Prueba)',
            'SECCION 03 - Formación Académica y Capacitación',
            'SECCION 04 - Experiencia Laboral',
            'SECCION 05 - Movimientos del Personal',
            'SECCION 06 - Compensaciones',
            'SECCION 07 - Evaluación de Desempeño. Progresión en la Carrera y Desplazamiento',
            'SECCION 08 - Reconocimientos y Sanciones Disciplinarias',
            'SECCION 09 - Relaciones Laborales Individuales y Colectivas',
            'SECCION 10 - Seguridad y Salud en el Trabajo y Bienestar Social',
            'SECCION 11 - Desvinculación',
        ];

        foreach ($seccions as $nombre_seccion) {
            DB::table('seccions')->insert([
                'nombre_seccion' => $nombre_seccion,
                'created_at' => now(),
            ]);
        }
    }
}