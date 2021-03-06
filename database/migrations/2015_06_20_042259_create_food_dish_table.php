<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodDishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //创建菜谱与食材的多对多 枢纽表
        Schema::create('food_dish', function (Blueprint $table) {
            $table -> integer('food_id');
            $table -> integer('dish_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('food_dish');
    }
}
