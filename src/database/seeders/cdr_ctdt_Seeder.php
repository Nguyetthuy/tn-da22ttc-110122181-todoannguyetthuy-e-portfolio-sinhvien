<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class cdr_ctdt_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cdr_ctdt')->insert([
            
            ['maCDR_CTDT'=>'1','maCDR_CTDT_VB'=>'ELO1','tenCDR_CTDT'=>'ELO1','maCT'=>1],
            ['maCDR_CTDT'=>'2','maCDR_CTDT_VB'=>'ELO2','tenCDR_CTDT'=>'ELO2','maCT'=>1],
            ['maCDR_CTDT'=>'3','maCDR_CTDT_VB'=>'ELO3','tenCDR_CTDT'=>'ELO3','maCT'=>1],
            ['maCDR_CTDT'=>'4','maCDR_CTDT_VB'=>'ELO4','tenCDR_CTDT'=>'ELO4','maCT'=>1],
            ['maCDR_CTDT'=>'5','maCDR_CTDT_VB'=>'ELO5','tenCDR_CTDT'=>'ELO5','maCT'=>1],
            ['maCDR_CTDT'=>'6','maCDR_CTDT_VB'=>'ELO6','tenCDR_CTDT'=>'ELO6','maCT'=>1],
            ['maCDR_CTDT'=>'7','maCDR_CTDT_VB'=>'ELO7','tenCDR_CTDT'=>'ELO7','maCT'=>1],
            ['maCDR_CTDT'=>'8','maCDR_CTDT_VB'=>'ELO8','tenCDR_CTDT'=>'ELO8','maCT'=>1],
            ['maCDR_CTDT'=>'9','maCDR_CTDT_VB'=>'ELO9','tenCDR_CTDT'=>'ELO9','maCT'=>1],
            ['maCDR_CTDT'=>'10','maCDR_CTDT_VB'=>'ELO10','tenCDR_CTDT'=>'ELO10','maCT'=>1],
            
         ]);
    }
}
