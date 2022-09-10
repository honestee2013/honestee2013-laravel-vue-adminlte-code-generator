<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();



        $roles_permissions = [
            'school-owner' => [
                'result' => ['view', 'create', 'update', 'delete'],
                'profile' => ['view', 'update'],
            ],
            'director' => [
                'result' => ['view', 'create', 'update', 'delete'],
                'profile' => ['view', 'update'],
            ],
            'principal' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'vice-principal' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'headmaster' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'assistant-headmaster' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'headteacher' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'assistant-headteacher' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'staff' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'class-form-master' => [
                'result' => ['view', 'create', 'update'],
                'profile' => ['view', 'update'],
            ],
            'teacher' => [
                'result' => ['view', 'create', 'update'],
                'profile' => ['view', 'update'],
            ],
            'parent' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'student' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'head-student' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'assistant-head-student' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'prefect' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'assistant-prefect' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'class-head' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
            'assistant-class-head' => [
                'result' => ['view'],
                'profile' => ['view', 'update'],
            ],
        ];


        foreach($roles_permissions as $rol => $resources){

            if(Role::where('name', $rol)->first())
                continue;

            $role = Role::create(['name' => $rol]);

            foreach($resources as $resource => $actions){
                foreach($actions as $action){
                    $permissionName = $action." ".$resource;
                    if(Permission::where('name', $permissionName)->first())
                        continue;

                    Permission::create(['name' => $permissionName]);
                    $role->syncPermissions($permissionName);
                }
            }
        }


    }
}