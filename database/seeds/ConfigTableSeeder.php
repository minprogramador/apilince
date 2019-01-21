<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		DB::table('configs')->insert([
			'id' => 1,
            'us' => 'cobraja',
            'pw' => '2525xpe',
            'usuario' => '1521',
            'cliente' => '1367',
            'url' => 'https://app.linceconsultadedados.com.br/',
            'cookie' => '',
            'token' => '',
            'proxy' => '191.252.186.96:3128',
            'status' => 1,
        ]);

    }
}
