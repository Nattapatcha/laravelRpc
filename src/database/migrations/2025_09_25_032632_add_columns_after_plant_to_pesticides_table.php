<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAfterPlantToPesticidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesticides', function (Blueprint $table) {
            if (!Schema::hasColumn('pesticides', 'limitation')) {
                $table->text('limitation')->nullable()->after('plant');
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
        Schema::table('pesticides', function (Blueprint $table) {
            if (Schema::hasColumn('pesticides', 'limitation')) {
                $table->dropColumn('limitation');
            }
        });
    }
}
