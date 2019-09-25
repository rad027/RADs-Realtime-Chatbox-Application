<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'chat-send',
           'profile-edit',
           'rs-send',
           'rs-list-history',
           'rs-list',
           'rs-update',
           'update-name',
           'dj-add',
           'dj-update',
           'dj-delete',
           'onboard-update',
           'role-admin',
           'role-coowner',
           'role-owner',
           'role-developer',
           'user-edit'
        ];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
