<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('company')->nullable()->after('address');
            $table->string('position')->nullable()->after('company');
            $table->string('website')->nullable()->after('position');
            $table->text('bio')->nullable()->after('website');
            $table->string('profile_photo')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'company', 'position', 'website', 'bio', 'profile_photo']);
        });
    }
};
