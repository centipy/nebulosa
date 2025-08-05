<?php

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
          Schema::table('customers', function (Blueprint $table) {
               // Añade las nuevas columnas después de 'advisor_observations'
               $table->date('last_visit')->nullable()->after('advisor_observations');
               $table->date('next_visit')->nullable()->after('last_visit');
               $table->boolean('visit_confirmed')->default(false)->after('next_visit');
          });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
          Schema::table('customers', function (Blueprint $table) {
               // Esto permite revertir los cambios si es necesario
               $table->dropColumn(['last_visit', 'next_visit', 'visit_confirmed']);
          });
     }
};
