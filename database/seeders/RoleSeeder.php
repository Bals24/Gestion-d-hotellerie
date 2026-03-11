<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache (important en dev)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Créer les permissions (optionnel mais utile pour granularité fine)
        $permissions = [
            'rooms.view', 'rooms.create', 'rooms.edit', 'rooms.delete',
            'reservations.view', 'reservations.create', 'reservations.edit', 'reservations.delete',
            'reservations.confirm', 'reservations.checkin', 'reservations.checkout', 'reservations.cancel',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'payments.view', 'payments.create',
            'exports.view', 'invoices.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ✅ Créer les rôles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $receptionist = Role::firstOrCreate(['name' => 'receptionist']);
        $accountant = Role::firstOrCreate(['name' => 'accountant']);

        // ✅ Assigner les permissions aux rôles
        $admin->givePermissionTo(Permission::all()); // Admin a tout

        $manager->givePermissionTo([
            'rooms.view', 'rooms.create', 'rooms.edit', 'rooms.delete',
            'reservations.view', 'reservations.create', 'reservations.edit', 'reservations.delete',
            'reservations.confirm', 'reservations.checkin', 'reservations.checkout', 'reservations.cancel',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'payments.view', 'payments.create',
            'exports.view', 'invoices.view',
        ]);

        $receptionist->givePermissionTo([
            'reservations.view', 'reservations.create', 'reservations.edit',
            'reservations.confirm', 'reservations.checkin', 'reservations.checkout', 'reservations.cancel',
            'clients.view', 'clients.create', 'clients.edit',
            'payments.view', 'payments.create',
        ]);

        $accountant->givePermissionTo([
            'payments.view', 'payments.create',
            'exports.view', 'invoices.view',
            'reservations.view', 'clients.view',
        ]);

        // ✅ Créer les utilisateurs de test avec leurs rôles
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@hotel.mg'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('123'),
            ]
        );
        $adminUser->assignRole('admin');

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@hotel.mg'],
            [
                'name' => 'Marie Manager',
                'password' => Hash::make('123'),
            ]
        );
        $managerUser->assignRole('manager');

        $receptionistUser = User::firstOrCreate(
            ['email' => 'reception@hotel.mg'],
            [
                'name' => 'Andry Réception',
                'password' => Hash::make('123'),
            ]
        );
        $receptionistUser->assignRole('receptionist');

        $accountantUser = User::firstOrCreate(
            ['email' => 'compta@hotel.mg'],
            [
                'name' => 'Niry Compta',
                'password' => Hash::make('123'),
            ]
        );
        $accountantUser->assignRole('accountant');

        $this->command->info('✅ Rôles et utilisateurs créés avec Spatie !');
    }
}