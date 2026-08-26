<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->decimal('price', 12, 2);
            $table->unsignedSmallInteger('engine_cc')->nullable();
            $table->unsignedInteger('mileage')->default(0);
            $table->enum('condition', ['new', 'used'])->default('used');
            $table->enum('transmission', ['automatic', 'manual'])->default('manual');
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid'])->default('petrol');
            $table->string('color')->nullable();
            $table->string('location');
            $table->boolean('featured')->default(false);
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->string('main_image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending');
            $table->timestamps();

            $table->index(['brand_id', 'status', 'price']);
            $table->index('title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
