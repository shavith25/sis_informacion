<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos=[
            'Administrador',
            'Supervisor',
            'Encargado',
            'Técnico Gobernación',
            'Técnico Municipal'
            
        ];
        
        foreach($permisos as $permiso){
        Permission::create(['name'=>$permiso]);
        }
    }
}
