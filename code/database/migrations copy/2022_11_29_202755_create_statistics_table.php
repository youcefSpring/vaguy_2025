<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('social');
            $table->integer('followers');
            $table->float('average_interactions', 8, 2);
            $table->float('gender_men');
            $table->float('gender_women');
            $table->float('age_g_13');
            $table->float('age_g_18');
            $table->float('age_g_25');
            $table->float('age_g_35');
            $table->float('age_g_45');
            $table->float('age_g_55');
            $table->float('age_m_13');
            $table->float('age_m_18');
            $table->float('age_m_25');
            $table->float('age_m_35');
            $table->float('age_m_45');
            $table->float('age_m_55');
            $table->float('age_w_13');
            $table->float('age_w_18');
            $table->float('age_w_25');
            $table->float('age_w_35');
            $table->float('age_w_45');
            $table->float('age_w_55');
            $table->string('city_1');
            $table->float('nomber_followers_1');
            $table->string('city_2');
            $table->float('nomber_followers_2');
            $table->string('city_3');
            $table->float('nomber_followers_3');
            $table->string('city_4');
            $table->float('nomber_followers_4');
            $table->foreignId('influencer_id')->constrained();//this line define the foreign key (reference the influnecer model)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
};
