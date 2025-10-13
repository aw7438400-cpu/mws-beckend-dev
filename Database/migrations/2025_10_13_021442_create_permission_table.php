<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        // permissions
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        // roles
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        // model_has_permissions
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid($columnNames['permission_pivot_key']); // permission_uuid
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']); // user_id integer
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign($columnNames['permission_pivot_key'])
                ->references('uuid')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            $table->primary([$columnNames['permission_pivot_key'], $columnNames['model_morph_key'], 'model_type']);
        });

        // model_has_roles
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid($columnNames['role_pivot_key']); // role_uuid
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']); // user_id integer
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign($columnNames['role_pivot_key'])
                ->references('uuid')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            $table->primary([$columnNames['role_pivot_key'], $columnNames['model_morph_key'], 'model_type']);
        });

        // role_has_permissions
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid($columnNames['permission_pivot_key']); // permission_uuid
            $table->uuid($columnNames['role_pivot_key']);       // role_uuid
            $table->foreign($columnNames['permission_pivot_key'])
                ->references('uuid')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            $table->foreign($columnNames['role_pivot_key'])
                ->references('uuid')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            $table->primary([$columnNames['permission_pivot_key'], $columnNames['role_pivot_key']]);
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};