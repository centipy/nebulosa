<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Schema;

class CustomersExport implements FromCollection, WithHeadings
{
     /**
      * @return \Illuminate\Support\Collection
      */
     public function collection()
     {
          // Obtiene todos los clientes con todas sus columnas para exportar
          return Customer::all();
     }

     /**
      * Define los encabezados de las columnas en el archivo Excel.
      * Esto tomará dinámicamente todos los nombres de las columnas de la tabla.
      *
      * @return array
      */
     public function headings(): array
     {
          // Obtiene todos los nombres de las columnas de la tabla 'customers'
          return Schema::getColumnListing('customers');
     }
}
