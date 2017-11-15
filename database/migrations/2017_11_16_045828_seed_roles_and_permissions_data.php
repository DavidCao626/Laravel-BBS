<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Spatie\Permission\Models\Role;//角色模型
use Spatie\Permission\Models\Permission;//权限模型

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'manage_contents']);//内容关联权限
        Permission::create(['name' => 'manage_users']);//用户关联权限
        Permission::create(['name' => 'edit_settings']);//编辑设定权限
        Permission::create(['name' => 'vip_video']);//VIP视频观看权限

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');
        $founder->givePermissionTo('vip_video');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');

        // 创建vip角色，并赋予权限
        $vip= Role::create(['name' => 'vip']);
        $vip->givePermissionTo('vip_video');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清除缓存
       app()['cache']->forget('spatie.permission.cache');

       // 清空所有数据表数据
       $tableNames = config('permission.table_names');

       Model::unguard();
       DB::table($tableNames['role_has_permissions'])->delete();
       DB::table($tableNames['model_has_roles'])->delete();
       DB::table($tableNames['model_has_permissions'])->delete();
       DB::table($tableNames['roles'])->delete();
       DB::table($tableNames['permissions'])->delete();
       Model::reguard();
    }
}
