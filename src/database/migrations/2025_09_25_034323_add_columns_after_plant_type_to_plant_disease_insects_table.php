<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAfterPlantTypeToPlantDiseaseInsectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plant_disease_insects', function (Blueprint $table) {
           if (!Schema::hasColumn('plant_disease_insects', 'limitation')) {
                $table->text('limitation')->nullable()->after('plant_type');
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
        Schema::table('plant_disease_insects', function (Blueprint $table) {
              if (Schema::hasColumn('plant_disease_insects', 'limitation')) {
                $table->dropColumn('limitation');
            }
        });
    }
}
