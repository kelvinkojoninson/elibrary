<?php

namespace Database\Seeders;

use App\Models\ModuleGroups;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModuleGroups::truncate();

        $list =  [
            [
                'title' => 'Dashboards',
            ],
            [
                'title' => 'Datalist',
            ],
            [
                'title' => 'System',
            ],  
        ];

        foreach ($list as $i) {
             ModuleGroups::create($i);
        }
    }
}
