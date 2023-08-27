<?php

use App\Models\SubStrand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubStrand::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name', 500);
            $table->text('highly_competent');
            $table->text('competent');
            $table->text('approaching_competence');
            $table->text('needs_improvement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
