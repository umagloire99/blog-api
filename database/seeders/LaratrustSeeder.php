<?php

namespace Database\Seeders;

use App\Helpers\GeneralHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->truncateRoleTable();

        if ($roles = config('laratrust_seeder.roles_structure')) {
            foreach ($roles as $key => $modules) {
                $this->command->info('Creating Role ' . strtoupper($key));

                // Create a new role
                $role = Role::firstOrCreate([
                    'name' => $key,
                    'display_name' => ucwords(str_replace('_', ' ', $key)),
                    'description' => ucwords(str_replace('_', ' ', $key))
                ]);

                $mapPermission = collect(config('laratrust_seeder.permissions_map'));
                $permissions = [];

                // Reading role permission modules
                foreach ($modules as $module => $value) {

                    foreach (explode(',', $value) as $p => $perm) {
                        $permissionValue = $mapPermission->get($perm);

                        $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);

                        $permissions[] = Permission::firstOrCreate([
                            'name' => $module . '-' . $permissionValue,
                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        ])->id;
                    }
                }

                // Attach all permissions to the role
                $role->permissions()->sync($permissions);

                if (config('laratrust_seeder.create_users')) {
                    $this->command->info("Creating '{$key}' user");

                    // Create default user for each role
                    $user = User::create([
                        'name' => ucwords(str_replace('_', ' ', $key)),
                        'email' => $key . '@blog.com',
                        'username' => GeneralHelper::generateUsername(),
                        'email_verified_at' => now(),
                        'password' => bcrypt('password')
                    ]);

                    $user->addRole($role);
                }
            }
        } else {
            $this->command->error("The configuration has not been published. Did you run `php artisan vendor:publish --tag=\"laratrust-seeder\"`");
        }
    }

    /**
     * Truncate RoleTable
     *
     */
    private function truncateRoleTable()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();

        if (config('laratrust_seeder.truncate_tables')) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (config('laratrust_seeder.create_users')) {
                DB::table('users');
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
