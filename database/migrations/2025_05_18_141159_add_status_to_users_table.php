<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom status bertipe string dengan default 'active'
            $table->string('status')->default('active')->after('role_id');
            
            // Jika ingin enum, ganti dengan ini (pastikan DB support enum):
            // $table->enum('status', ['active', 'inactive'])->default('active')->after('role_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
