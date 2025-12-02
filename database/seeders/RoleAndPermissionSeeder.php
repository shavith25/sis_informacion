<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de permisos por módulo para una mejor organización
        $permissionsByModule = [
            'Usuarios' => ['ver-usuario', 'crear-usuario', 'editar-usuario', 'borrar-usuario'],
            'Roles' => ['ver-rol', 'crear-rol', 'editar-rol', 'borrar-rol'],
            'Áreas' => ['ver-area', 'crear-area', 'editar-area', 'borrar-area'],
            'Zonas' => ['ver-zona', 'crear-zona', 'editar-zona', 'borrar-zona', 'exportar-zona'],
            'Mapas' => ['ver-mapa', 'ver-mapa-areas'],
            
            // LÍMITES - Permisos separados por entidad
            'Límites' => [
                'ver-limites',
                'crear-departamento', 'editar-departamento', 'borrar-departamento',
                'crear-provincia', 'editar-provincia', 'borrar-provincia',
                'crear-municipio', 'editar-municipio', 'borrar-municipio',
            ],

            'Sugerencias' => ['ver-sugerencias', 'aprobar-sugerencias', 'eliminar-sugerencias'],
            'Comentarios' => ['ver-comentarios', 'aprobar-comentarios', 'eliminar-comentarios'],
            'Reportes Ambientales' => ['ver-reportes', 'aprobar-reportes', 'eliminar-reportes'],
            'Media Comunidad' => ['ver-media', 'aprobar-media', 'eliminar-media'],
            'Especies' => ['ver-especie', 'crear-especie', 'editar-especie', 'borrar-especie'],
            'Noticias' => ['ver-noticia', 'crear-noticia', 'editar-noticia', 'borrar-noticia'],
            'Marco Normativo' => ['ver-documento', 'crear-documento', 'editar-documento', 'borrar-documento'],
            'Panel Concientización' => [
                'ver-panel-concientizacion', 
                'crear-panel-concientizacion', 
                'editar-panel-concientizacion', 
                'borrar-panel-concientizacion'
            ],
            'Reportes Sistema' => ['ver-reporte'],
            'Panel de Ayuda' => ['ver-panel-ayuda', 'subir-ayuda', 'eliminar-ayuda'],
        ];

        // Crear permisos
        foreach ($permissionsByModule as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);

        // Asignar TODOS los permisos al Administrador
        $adminRole->syncPermissions(Permission::all());

        $this->command->info('✅ Roles y permisos creados exitosamente');
        $this->command->info('📊 Total de permisos: ' . Permission::count());
        $this->command->info('👥 Total de roles: ' . Role::count());
    }
}