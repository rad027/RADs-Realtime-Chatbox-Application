<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
        	'Developer',
        	'Owner',
        	'Co-Owner',
        	'Administrator',
        	'General Moderator',
        	'Moderator',
        	'Disc Jockey',
        	'Platinum VIP',
        	'Gold VIP',
        	'Silver VIP',
        	'Pro Member',
        	'Member',
        	'Banned'
        ];
        foreach($roles as $role){
        	Role::create([
        		'name'	=> $role
        	]);
        }
    }
}
