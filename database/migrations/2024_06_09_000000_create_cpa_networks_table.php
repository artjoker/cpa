<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cpa_networks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Назва CPA-мережі');
            $table->string('slug')->unique()->comment('Унікальний ідентифікатор (slug) для інтеграції');
            $table->string('base_url')->nullable()->comment('Базова URL-адреса мережі');
            $table->json('config')->nullable()->comment('Додаткові налаштування у форматі JSON');
            $table->boolean('is_active')->default(true)->comment('Чи активна мережа');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpa_networks');
    }
}; 