<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgIdcardSurnameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            

            // organization: int หลัง id
            if (!Schema::hasColumn('users', 'organization')) {
                $table->unsignedInteger('organization')
                    ->nullable()
                    ->after('id');
            }

            // surname: varchar หลัง name
            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname', 100)
                    ->nullable()
                    ->after('name');
            }

            // idcard: << เลือก 1 แบบ >>
            // แบบแนะนำ (ปลอดภัยกว่า): เก็บเป็น string เพราะ 13 หลักและอาจมีเลข 0 นำหน้า
            if (!Schema::hasColumn('users', 'idcard')) {
                $table->string('idcard', 32)
                    ->nullable()
                    ->after('type');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->unsignedTinyInteger('status')
                      ->default(1)
                      ->comment('1=active,0=inactive')
                      ->after('idcard');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organization')) {
                $table->dropColumn('organization');
            }
            if (Schema::hasColumn('users', 'surname')) {
                $table->dropColumn('surname');
            }
            if (Schema::hasColumn('users', 'idcard')) {
                $table->dropColumn('idcard');
            }
        });
    }
}
