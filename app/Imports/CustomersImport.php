<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Para manejar fechas de Excel

class CustomersImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
     /**
      * @param array $row
      *
      * @return \Illuminate\Database\Eloquent\Model|null
      */
     public function model(array $row)
     {
          // Función auxiliar para parsear fechas de Excel
          $parseExcelDate = function ($excelDate) {
               if (empty($excelDate)) {
                    return null;
               }
               if (is_numeric($excelDate)) {
                    // Convertir fecha numérica de Excel a formato de fecha de PHP
                    return Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
               }
               // Si no es numérica, intentar parsear como string
               try {
                    return \Carbon\Carbon::parse($excelDate)->format('Y-m-d');
               } catch (\Exception $e) {
                    return null;
               }
          };

          // Mapea las columnas del CSV/Excel a los campos de tu modelo Customer
          // Concatena los campos de nombre para crear el 'full_name'
          $fullName = trim(($row['nombre'] ?? '') . ' ' . ($row['segundo_nombre'] ?? '') . ' ' . ($row['apellido_paterno'] ?? '') . ' ' . ($row['apellido_materno'] ?? ''));

          return new Customer([
               'customer_id' => $row['de_cliente'] ?? null,
               'customer_type' => $row['cliente'] ?? null,
               'full_name' => $fullName,
               'address' => $row['direccion'] ?? null,
               'city' => $row['ciudad'] ?? null,
               'state' => $row['estado'] ?? null,
               'zip_code' => $row['codigo_postal'] ?? null,
               'status' => $row['status'] ?? null,
               'first_name' => $row['nombre'] ?? null,
               'middle_name' => $row['segundo_nombre'] ?? null,
               'paternal_last_name' => $row['apellido_paterno'] ?? null,
               'maternal_last_name' => $row['apellido_materno'] ?? null,
               'email' => $row['correo_electronico'] ?? null,
               'home_phone' => $row['telefono_de_casa'] ?? null,
               'work_phone' => $row['telefono_del_trabajo'] ?? null,
               'mobile_phone' => $row['telefono_movil'] ?? null,
               'distributor' => $row['distribuidor'] ?? null,
               'seller' => $row['vendedor'] ?? null,
               'level' => $row['nivel'] ?? null,
               'credit_limit' => $row['limite_de_credito'] ?? null,
               'date_increased' => $parseExcelDate($row['fecha_aumentada'] ?? null),
               'increased_by' => $row['aumentado_en'] ?? null,
               'available_credit' => $row['credito_disponible'] ?? null,
               'monthly_payment' => $row['mensualidad'] ?? null,
               'closing_date' => $parseExcelDate($row['fecha_de_cierre'] ?? null),
               'current_balance' => $row['saldo_actual'] ?? null,
               'current_amount' => $row['cantidad_actual'] ?? null,
               'due_0_30_days' => $row['0_30_dias_de_morosidad'] ?? null,
               'due_31_60_days' => $row['31_60_dias_de_morosidad'] ?? null,
               'due_61_90_days' => $row['61_90_dias_de_morosidad'] ?? null,
               'due_over_90_days' => $row['sobre_90_dias_de_morosidad'] ?? null,
               'original_order_date' => $parseExcelDate($row['fecha_de_orden_original'] ?? null),
               'last_purchase_date' => $parseExcelDate($row['ultima_fecha_de_compra'] ?? null),
               'last_payment_date' => $parseExcelDate($row['ultima_fecha_de_pago'] ?? null),
               'orders' => $row['pedidos'] ?? null,
               'birthday' => $parseExcelDate($row['fecha_de_cumpleanos'] ?? null),
               'telemarketing_observations' => $row['observaciones_telemarketing'] ?? null,
               'advisor_observations' => $row['observaciones_asesor'] ?? null,
          ]);
     }

     // Reglas de validación para cada fila
     public function rules(): array
     {
          return [
               'de_cliente' => 'required|string|unique:customers,customer_id', // Valida que el customer_id sea único
               'nombre' => 'required|string|max:255',
               'correo_electronico' => 'nullable|email|max:255',
               'telefono_movil' => 'nullable|string|max:20',
               // Puedes añadir más reglas de validación aquí para otros campos
          ];
     }

     // Personaliza los mensajes de validación (opcional)
     public function customValidationMessages()
     {
          return [
               'de_cliente.required' => 'El ID de cliente es obligatorio.',
               'de_cliente.unique' => 'El ID de cliente ya existe en la base de datos.',
               'nombre.required' => 'El nombre es obligatorio.',
               'correo_electronico.email' => 'El correo electrónico no es válido.',
          ];
     }

     // Número de filas a insertar en cada lote
     public function batchSize(): int
     {
          return 1000; // Inserta 1000 filas a la vez para mejorar el rendimiento
     }

     // Número de filas a leer en cada "chunk" (para manejar archivos grandes)
     public function chunkSize(): int
     {
          return 1000; // Lee 1000 filas a la vez
     }
}
