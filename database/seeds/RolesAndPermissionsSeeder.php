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
      // create roles and assign created permissions

      // this can be done as separate statements
      $role = Role::firstOrCreate(['name' => 'admin']);

      // or may be done by chaining
      $role = Role::firstOrCreate(['name' => 'manager'])
          ->givePermissionTo(['view attendance sheet'])
          ->givePermissionTo(['export'])
          ->givePermissionTo(['invite']);

    }
}
