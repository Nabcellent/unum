<?php

use App\Models\Grade;
use App\Models\Team;
use App\Models\User;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Grade::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(User::class, 'tutor_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Team::class)->constrained();
            $table->string('admission_no', 10); //  TODO: Should this be a unique integer value?
            $table->tinyInteger('class_no');
            $table->date('dob');
            $table->string('citizenship', 20);
            $table->string('religion', 20);
            $table->string('denomination')->nullable();
            $table->string('previous_school', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
