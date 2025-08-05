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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('customer_id')->unique(); // ID de cliente del CSV
            $table->string('customer_type')->nullable(); // Tipo de cliente (HC)
            $table->string('full_name'); // Nombre completo
            $table->string('address')->nullable(); // Dirección
            $table->string('city')->nullable(); // Ciudad
            $table->string('state')->nullable(); // Estado
            $table->string('zip_code')->nullable(); // Código Postal
            $table->string('status')->nullable(); // Estatus (CURRENT)
            $table->string('first_name')->nullable(); // Nombre
            $table->string('middle_name')->nullable(); // Segundo Nombre
            $table->string('paternal_last_name')->nullable(); // Apellido Paterno
            $table->string('maternal_last_name')->nullable(); // Apellido Materno
            $table->string('email')->nullable(); // Correo Electrónico
            $table->string('home_phone')->nullable(); // Teléfono de Casa
            $table->string('work_phone')->nullable(); // Teléfono del Trabajo
            $table->string('mobile_phone')->nullable(); // Teléfono Móvil
            $table->string('distributor')->nullable(); // Distribuidor
            $table->string('seller')->nullable(); // Vendedor
            $table->integer('level')->nullable(); // Nivel
            $table->decimal('credit_limit', 10, 2)->nullable(); // Límite de Crédito
            $table->date('date_increased')->nullable(); // Fecha Aumentada
            $table->decimal('increased_by', 10, 2)->nullable(); // Aumentado En
            $table->decimal('available_credit', 10, 2)->nullable(); // Crédito Disponible
            $table->decimal('monthly_payment', 10, 2)->nullable(); // Mensualidad
            $table->date('closing_date')->nullable(); // Fecha de Cierre
            $table->decimal('current_balance', 10, 2)->nullable(); // Saldo Actual
            $table->decimal('current_amount', 10, 2)->nullable(); // Cantidad Actual
            $table->decimal('due_0_30_days', 10, 2)->nullable(); // 0-30 Días de Morosidad
            $table->decimal('due_31_60_days', 10, 2)->nullable(); // 31-60 Días de Morosidad
            $table->decimal('due_61_90_days', 10, 2)->nullable(); // 61-90 Días de Morosidad
            $table->decimal('due_over_90_days', 10, 2)->nullable(); // Sobre 90 Días de Morosidad
            $table->date('original_order_date')->nullable(); // Fecha de Orden Original
            $table->date('last_purchase_date')->nullable(); // Última Fecha de Compra
            $table->date('last_payment_date')->nullable(); // Última Fecha de Pago
            $table->text('orders')->nullable(); // Pedidos

            // Nuevos campos solicitados
            $table->date('birthday')->nullable(); // Fecha de Cumpleaños
            $table->text('telemarketing_observations')->nullable(); // Observaciones Telemarketing
            $table->text('advisor_observations')->nullable(); // Observaciones Asesor

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
