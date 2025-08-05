<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
     /**
      * Muestra una lista de clientes.
      */
     public function index(Request $request)
     {
          $search = $request->input('search');

          $customers = Customer::query()
               ->when($search, function ($query, $search) {
                    $query->where('full_name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%')
                         ->orWhere('mobile_phone', 'like', '%' . $search . '%')
                         ->orWhere('customer_id', 'like', '%' . $search . '%');
               })
               ->orderBy('full_name')
               ->paginate(10);

          return Inertia::render('Customers/Index', [
               'customers' => $customers,
               'filters' => $request->only(['search']),
          ]);
     }

     /**
      * Muestra el formulario para crear un nuevo cliente.
      */
     public function create()
     {
          return Inertia::render('Customers/Create');
     }

     /**
      * Almacena un nuevo cliente en la base de datos.
      */
     public function store(Request $request)
     {
          $validatedData = $request->validate([
               'customer_id' => 'required|unique:customers,customer_id',
               'full_name' => 'required|string|max:255',
               'email' => 'nullable|email|max:255',
               'mobile_phone' => 'nullable|string|max:20',
               'birthday' => 'nullable|date',
               'telemarketing_observations' => 'nullable|string',
               'advisor_observations' => 'nullable|string',
          ]);

          Customer::create($validatedData);

          return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente.');
     }

     /**
      * Muestra los detalles de un cliente específico.
      */
     public function show(Customer $customer)
     {
          return Inertia::render('Customers/Show', [
               'customer' => $customer,
          ]);
     }

     /**
      * Muestra el formulario para editar un cliente existente.
      */
     public function edit(Customer $customer)
     {
          return Inertia::render('Customers/Edit', [
               'customer' => $customer,
          ]);
     }

     /**
      * Actualiza un cliente existente en la base de datos.
      */
     public function update(Request $request, Customer $customer)
     {
          $validatedData = $request->validate([
               'customer_id' => 'required|unique:customers,customer_id,' . $customer->id,
               'full_name' => 'required|string|max:255',
               'email' => 'nullable|email|max:255',
               'mobile_phone' => 'nullable|string|max:20',
               'birthday' => 'nullable|date',
               'telemarketing_observations' => 'nullable|string',
               'advisor_observations' => 'nullable|string',
          ]);

          $customer->update($validatedData);

          // ESTA LÍNEA ES CRUCIAL:
          // Redirige a la página de índice para forzar la recarga de la lista de clientes.
          return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente.');
     }

     /**
      * Elimina un cliente de la base de datos.
      */
     public function destroy(Customer $customer)
     {
          $customer->delete();

          return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente.');
     }
}
