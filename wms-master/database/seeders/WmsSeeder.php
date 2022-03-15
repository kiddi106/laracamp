<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = '2021-01-01 00:00:00';
        DB::table('mst_status')->insert([
            ['id' => 1, 'name' => 'Requested', 'bgcolor' => 'bg-warning', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 2, 'name' => 'Reworked', 'bgcolor' => 'bg-olive', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 3, 'name' => 'Received', 'bgcolor' => 'bg-lightblue', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 4, 'name' => 'Pick Up Created', 'bgcolor' => 'bg-info', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 5, 'name' => 'Waiting For Courier', 'bgcolor' => 'bg-secondary', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 6, 'name' => 'Picked Up', 'bgcolor' => 'bg-primary', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 7, 'name' => 'Canceled', 'bgcolor' => 'bg-red', 'created_at' => $created_at, 'updated_at' => $created_at],
        ]);

        DB::table('purchase_types')->insert([
            ['id' => 1, 'name' => 'ONLINE', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 2, 'name' => 'OFFLINE', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 3, 'name' => 'O2O', 'created_at' => $created_at, 'updated_at' => $created_at],
        ]);

        DB::table('material_type')->insert([
            ['id' => 1, 'name' => 'Router', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 2, 'name' => 'SIM Card', 'created_at' => $created_at, 'updated_at' => $created_at],
        ]);

        DB::table('material')->insert([
            ['id' => 1, 'name' => 'MF283U', 'type_id' => 1, 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 2, 'name' => 'TSel Orbit', 'type_id' => 2, 'created_at' => $created_at, 'updated_at' => $created_at],
        ]);

        $vp_operational = Role::create([
            'name' => 'vp-operational',
            'display_name' => 'VP Operational',
            'description' => 'User is the Vice President of Operational'
        ]);
        $user = User::query()->where('email', '=', 'bambang.suryadi@mitracomm.com')->first();
        if ($user) {
            $user->attachRole($vp_operational);
        }

        $manager_operational = Role::create([
            'name' => 'manager-operational',
            'display_name' => 'Manager Operational',
            'description' => 'User is the Manager of Operational'
        ]);
        $user = User::query()->where('email', '=', 'dameria.gultom@mitracomm.com')->first();
        if ($user) {
            $user->attachRole($manager_operational);
        }

        $manager_warehouse = Role::create([
            'name' => 'manager-warehouse',
            'display_name' => 'Manager Warehouse',
            'description' => 'User is the Manager of Warehouse'
        ]);
        $user = User::query()->where('email', '=', 'budi.rinaldo@mitracomm.com')->first();
        if ($user) {
            $user->attachRole($manager_operational);
        }

        $staff_warehouse = Role::create([
            'name' => 'staff-warehouse',
            'display_name' => 'Staff Warehouse',
            'description' => 'User is the Staff of Warehouse'
        ]);
        $denny = User::query()->where('email', '=', 'denny.saputra@mitracomm.com')->first();
        if ($denny) {
            $denny->attachRole($staff_warehouse);
        }

        $staff_bispro = Role::create([
            'name' => 'staff-bispro',
            'display_name' => 'Staff Business Process & Document Preparation',
            'description' => 'User is the Staff of Business Process & Document Preparation'
        ]);
        $user = User::query()->where('email', '=', 'asri.makale@mitracomm.com')->first();
        if ($user) {
            $user->attachRole($staff_bispro);
        }

        $orbit = Role::create([
            'name' => 'client-orbit',
            'display_name' => 'Orbit',
            'description' => 'User is the vendor of WMS'
        ]);
        $user = User::query()->where('email', '=', 'orbit@mail.com')->first();
        if ($user) {
            $user->attachRole($orbit);
        }

        $menus = [
            ['id' => 6, 'menu_id' => null, 'name' => 'po', 'display_name' => 'Receive Purchase Order', 'order_no' => 1, 'url' => '', 'icon' => 'fas fa-inbox', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 7, 'menu_id' => 6, 'name' => 'input', 'display_name' => 'Input', 'order_no' => 1, 'url' => '/po/create', 'icon' => 'fas fa-plus', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 8, 'menu_id' => 6, 'name' => 'list', 'display_name' => 'List', 'order_no' => 2, 'url' => '/po', 'icon' => 'fas fa-table', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 9, 'menu_id' => null, 'name' => 'rework', 'display_name' => 'Rework', 'order_no' => 2, 'url' => '/rework', 'icon' => 'fas fa-cog', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 10, 'menu_id' => null, 'name' => 'pickpack', 'display_name' => 'Pickpack', 'order_no' => 3, 'url' => '', 'icon' => 'fas fa-box', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 11, 'menu_id' => 10, 'name' => 'input', 'display_name' => 'Input', 'order_no' => 1, 'url' => '/pickpack/create', 'icon' => 'fas fa-plus', 'created_at' => $created_at, 'updated_at' => $created_at],
            ['id' => 12, 'menu_id' => 10, 'name' => 'list', 'display_name' => 'List', 'order_no' => 2, 'url' => '/pickpack', 'icon' => 'fas fa-table', 'created_at' => $created_at, 'updated_at' => $created_at],
        ];
        DB::table('menus')->insert($menus);

        $administrator = Role::query()->where('name', '=', 'administrator')->first();

        $roles = [
            $administrator, $vp_operational, $manager_operational, $manager_warehouse, $staff_warehouse, $staff_bispro
        ];

        foreach ($roles as $role) {
            $menu_role = [];
            foreach ($menus as $menu) {
                $menu_role[] = ['menu_id' => $menu['id'], 'role_id' => $role->id];
            }
            DB::table('menu_role')->insert($menu_role);
        }

        $read_po = Permission::create(['name' => 'read-po', 'display_name' => ucwords('read po'), 'description' => ucwords('read po')]);
        $create_po = Permission::create(['name' => 'create-po', 'display_name' => ucwords('create po'), 'description' => ucwords('create po')]);
        $update_po = Permission::create(['name' => 'update-po', 'display_name' => ucwords('update po'), 'description' => ucwords('update po')]);
        $delete_po = Permission::create(['name' => 'delete-po', 'display_name' => ucwords('delete po'), 'description' => ucwords('delete po')]);
        $read_rework = Permission::create(['name' => 'read-rework', 'display_name' => ucwords('read rework'), 'description' => ucwords('read rework')]);
        $create_rework = Permission::create(['name' => 'create-rework', 'display_name' => ucwords('create rework'), 'description' => ucwords('create rework')]);
        $update_rework = Permission::create(['name' => 'update-rework', 'display_name' => ucwords('update rework'), 'description' => ucwords('update rework')]);
        $delete_rework = Permission::create(['name' => 'delete-rework', 'display_name' => ucwords('delete rework'), 'description' => ucwords('delete rework')]);
        $read_pickpack = Permission::create(['name' => 'read-pickpack', 'display_name' => ucwords('read pickpack'), 'description' => ucwords('read pickpack')]);
        $create_pickpack = Permission::create(['name' => 'create-pickpack', 'display_name' => ucwords('create pickpack'), 'description' => ucwords('create pickpack')]);
        $update_pickpack = Permission::create(['name' => 'update-pickpack', 'display_name' => ucwords('update pickpack'), 'description' => ucwords('update pickpack')]);
        $delete_pickpack = Permission::create(['name' => 'delete-pickpack', 'display_name' => ucwords('delete pickpack'), 'description' => ucwords('delete pickpack')]);
        $read_orbit_stock = Permission::create(['name' => 'read-orbit-stock', 'display_name' => ucwords('read orbit stock'), 'description' => ucwords('read orbit stock')]);
        $create_orbit_stock = Permission::create(['name' => 'create-orbit-stock', 'display_name' => ucwords('create orbit stock'), 'description' => ucwords('create orbit stock')]);
        $update_orbit_stock = Permission::create(['name' => 'update-orbit-stock', 'display_name' => ucwords('update orbit stock'), 'description' => ucwords('update orbit stock')]);
        $delete_orbit_stock = Permission::create(['name' => 'delete-orbit-stock', 'display_name' => ucwords('delete orbit stock'), 'description' => ucwords('delete orbit stock')]);
        $read_orbit_order = Permission::create(['name' => 'read-orbit-order', 'display_name' => ucwords('read orbit order'), 'description' => ucwords('read orbit order')]);
        $create_orbit_order = Permission::create(['name' => 'create-orbit-order', 'display_name' => ucwords('create orbit order'), 'description' => ucwords('create orbit order')]);
        $update_orbit_order = Permission::create(['name' => 'update-orbit-order', 'display_name' => ucwords('update orbit order'), 'description' => ucwords('update orbit order')]);
        $delete_orbit_order = Permission::create(['name' => 'delete-orbit-order', 'display_name' => ucwords('delete orbit order'), 'description' => ucwords('delete orbit order')]);

        foreach ($roles as $role) {
            $role->attachPermissions([$read_po, $create_po, $update_po, $delete_po, $read_rework, $create_rework, $update_rework, $delete_rework, $read_pickpack, $create_pickpack, $update_pickpack, $delete_pickpack, $read_orbit_stock, $create_orbit_stock, $update_orbit_stock, $delete_orbit_stock]);
        }

        $permissionOrbit = [$create_orbit_stock, $read_orbit_order, $create_orbit_order, $update_orbit_order];
        $orbit->attachPermissions($permissionOrbit);

        DB::table('po')->insert([
            ['id' => 1, 'po_no' => 'PO/048/02/2021/PT', 'po_at' => '2021-02-05', 'delivery_no' => 'PIB AJU 002717', 'receive_at' => '2021-03-25', 'description' => '-', 'currency' => 'Rp', 'kurs' => 1, 'created_at' => '2021-05-03 11:49:23', 'created_by' => $denny->id],
            ['id' => 2, 'po_no' => 'PO/001/03/2021/ME', 'po_at' => '2021-04-06', 'delivery_no' => 'DO-006DCSDV-202104-0033', 'receive_at' => '2021-04-28', 'description' => '-', 'currency' => 'Rp', 'kurs' => 1, 'created_at' => '2021-05-17 11:29:23.0', 'created_by' => $denny->id]
        ]);

        DB::table('po_dtl')->insert([
            ['po_id' => 1, 'material_id' => 1, 'qty' => 5100, 'uom' => 'pcs', 'price' => 0, 'total' => 0, 'description' => '', 'created_at' => '2021-05-03 11:49:23', 'created_by' => $denny->id],
            ['po_id' => 2, 'material_id' => 2, 'qty' => 25000, 'uom' => 'pcs', 'price' => 0, 'total' => 0, 'description' => '', 'created_at' => '2021-05-17 11:29:23.0', 'created_by' => $denny->id]
        ]);
    }
}
