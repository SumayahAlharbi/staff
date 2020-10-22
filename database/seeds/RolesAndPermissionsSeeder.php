<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Reset cached roles and permissions
      app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

      // create permissions
      Permission::firstOrCreate(['name' => 'view attendance sheet']);
      Permission::firstOrCreate(['name' => 'export']);
      Permission::firstOrCreate(['name' => 'invite']);
      Permission::firstOrCreate(['name' => 'edit users']);
      Permission::firstOrCreate(['name' => 'add role']);
      Permission::firstOrCreate(['name' => 'remove role']);
      Permission::firstOrCreate(['name' => 'add permission']);
      Permission::firstOrCreate(['name' => 'remove permission']);
      // create roles and assign created permissions

      // this can be done as separate statements
      $role = Role::firstOrCreate(['name' => 'admin']);

      // or may be done by chaining
      $role = Role::firstOrCreate(['name' => 'manager'])
          ->givePermissionTo(['view attendance sheet'])
          ->givePermissionTo(['export'])
          ->givePermissionTo(['invite'])
          ->givePermissionTo(['edit users'])
          ->givePermissionTo(['add role'])
          ->givePermissionTo(['remove role'])
          ->givePermissionTo(['add permission'])
          ->givePermissionTo(['remove permission']);

    }
}
