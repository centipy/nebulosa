<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel; // Importa el Facade de Laravel Excel
use App\Imports\CustomersImport; // Importa la clase de importación que crearemos

class CustomerImportController extends Controller
{
    /**
     * Muestra el formulario para subir el archivo Excel.
     */
    public function create()
    {
        return Inertia::render('Customers/Import');
    }

    /**
     * Procesa el archivo Excel subido.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv|max:10240', // Validar que sea un archivo Excel/CSV y el tamaño
        ]);

        try {
            // Importa el archivo usando la clase CustomersImport
            Excel::import(new CustomersImport, $request->file('file'));

            return redirect()->route('customers.index')->with('success', 'Clientes importados exitosamente.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Fila {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return redirect()->back()->withErrors(['import_errors' => $errors]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al importar los clientes: ' . $e->getMessage());
        }
    }
}
