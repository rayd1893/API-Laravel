<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run()
    {
        $this->createPeruConfigurations();
    }

    public function createPeruConfigurations()
    {
        // Perú
        Configuration::create([
            'section' => 'fares',
            'country' => 'PER',
            'options' => [
                'costs' => [
                    ['distance' => 5, 'price' => 8.7],
                    ['distance' => 10, 'price' => 16],
                    ['distance' => 15, 'price' => 23.2],
                    ['distance' => 25, 'price' => 37.7],
                ]
            ],
        ]);

        Configuration::create([
            'section' => 'commissions',
            'country' => 'PER',
            'options' => [
                'percentage' => 70,
            ],
        ]);

        // México
        Configuration::create([
            'section' => 'fares',
            'country' => 'MEX',
            'options' => [
                'costs' => [
                    ['distance' => 5, 'price' => 39],
                    ['distance' => 15, 'price' => 64],
                    ['distance' => 30, 'price' => 99],
                    ['distance' => 31, 'price' => 159],
                ]
            ],
        ]);

        Configuration::create([
            'section' => 'commissions',
            'country' => 'MEX',
            'options' => [
                'percentage' => 70,
            ],
        ]);
    }
}
