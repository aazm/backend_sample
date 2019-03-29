<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeneralCleanup extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = array_map('reset', DB::select('SHOW TABLES'));

        collect($tables)->reject(function($name){
            return in_array($name, ['migrations','users', 'password_resets']);
        })->map(function($name){
            DB::statement('ALTER TABLE ' . $name . ' ENGINE = InnoDB');
        });



        Schema::table('attribute', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->string('name', 100)->unique()->change();
        });

        Schema::table('attribute_value', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->unique(['attribute_id', 'value']);
            $table->foreign('attribute_id')->references('attribute_id')->on('attribute');
        });

        Schema::table('product_attribute', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->foreign('attribute_value_id')->references('attribute_value_id')->on('attribute_value');
        });

        Schema::table('product_category', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->foreign('product_id')->references('product_id')->on('product');
            $table->foreign('category_id')->references('category_id')->on('category');
        });

        Schema::table('category', function (\Illuminate\Database\Schema\Blueprint $table){

            $table->text('description')->nullable()->change();
            $table->unique(['department_id', 'name']);
            $table->foreign('department_id')->references('department_id')->on('department');

        });

        Schema::table('department', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->string('name', 100)->unique()->change();
            $table->text('description')->nullable()->change();
        });

        DB::statement('ALTER TABLE review DROP INDEX idx_review_customer_id');
        DB::statement('ALTER TABLE review DROP INDEX idx_review_product_id');

        Schema::table('review', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->foreign('customer_id')->references('customer_id')->on('customer');
            $table->foreign('product_id')->references('product_id')->on('product');

        });

        // doesn't looks like a region
        DB::statement('DELETE FROM shipping_region WHERE shipping_region = "Please Select"');

        Schema::table('shipping_region', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->string('shipping_region', 100)->unique()->change();
        });

        Schema::table('shopping_cart', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->unique(['cart_id', 'product_id']);
            $table->integer('customer_id'); // customer is missing in table, card_id have no sense in that case. but will keep it

            $table->foreign('product_id')->references('product_id')->on('product');
            $table->foreign('customer_id')->references('customer_id')->on('customer');
        });

        Schema::table('customer', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->string('password')->change();
            $table->rememberToken();
        });
        Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->foreign('shipping_id')->references('shipping_id')->on('shipping');
            $table->foreign('customer_id')->references('customer_id')->on('customer');
            $table->foreign('tax_id')->references('tax_id')->on('tax');
        });
        Schema::table('order_detail', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->foreign('product_id')->references('product_id')->on('product');
        });

        DB::statement('ALTER TABLE product DROP INDEX idx_ft_product_name_description');

        Schema::table('product', function (\Illuminate\Database\Schema\Blueprint $table){
            $table->index('name');
            $table->index('description');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new \RuntimeException('unable to rollback');
    }
}
