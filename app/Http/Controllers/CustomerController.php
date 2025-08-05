<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // Asegúrate de que Carbon está importado

class CustomerController extends Controller
{
     /**
      * Muestra una lista de clientes.
      */
     public function index(Request $request)
     {
          $customers = Customer::query()
               ->when($request->input('search'), function ($query, $search) {
                    $query->where('full_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('home_phone', 'like', "%{$search}%")
                         ->orWhere('mobile_phone', 'like', "%{$search}%")
                         ->orWhere('work_phone', 'like', "%{$search}%")
                         ->orWhere('customer_id', 'like', "%{$search}%");
               })
               ->paginate(10)
               ->withQueryString()
               // --- LÓGICA DE RESETEO AUTOMÁTICO AÑADIDA AQUÍ ---
               ->through(function ($customer) {
                    $isConfirmedForUI = $customer->visit_confirmed;

                    // Si la visita está marcada como confirmada en la BD...
                    if ($customer->visit_confirmed && $customer->next_visit) {
                         // ...pero la fecha de la próxima visita ya pasó...
                         if (Carbon::parse($customer->next_visit)->isPast()) {
                              // ...entonces para la UI, la consideramos "no confirmada" (pendiente de nuevo).
                              $isConfirmedForUI = false;
                         }
                    }

                    return [
                         'id' => $customer->id,
                         'customer_id' => $customer->customer_id,
                         'full_name' => $customer->full_name,
                         'email' => $customer->email,
                         'mobile_phone' => $customer->mobile_phone,
                         'birthday' => $customer->birthday,
                         'telemarketing_observations' => $customer->telemarketing_observations,
                         'advisor_observations' => $customer->advisor_observations,
                         'last_visit' => $customer->last_visit,
                         'next_visit' => $customer->next_visit,
                         'visit_confirmed' => $isConfirmedForUI, // Enviamos el estado calculado
                    ];
               });

          return Inertia::render('Customers/Index', [
               'customers' => $customers,
               'filters' => $request->only(['search']),
          ]);
     }

     /**
      * Exporta los clientes a un archivo Excel.
      */
     public function export()
     {
          return Excel::download(new CustomersExport, 'clientes.xlsx');
     }

     /**
      * Confirma una visita, actualiza la última visita y programa la siguiente.
      */
     public function confirmVisit(Customer $customer)
     {
          // --- LÓGICA CORREGIDA ---
          $customer->update([
               'visit_confirmed' => true, // Se marca como TRUE para este ciclo
               'last_visit' => Carbon::now(),
               'next_visit' => Carbon::now()->addMonths(6),
          ]);

          return back()->with('success', 'Visita confirmada y reagendada exitosamente.');
     }

     // ... (El resto de tus métodos: create, store, show, etc. se mantienen igual) ...

     public function create()
     {
          return Inertia::render('Customers/Create');
     }

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
               'last_visit' => 'nullable|date',
               'next_visit' => 'nullable|date',
          ]);

          Customer::create($validatedData);

          return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente.');
     }

     public function show(Customer $customer)
     {
          return Inertia::render('Customers/Show', [
               'customer' => $customer,
          ]);
     }

     public function edit(Customer $customer)
     {
          return Inertia::render('Customers/Edit', [
               'customer' => $customer,
          ]);
     }

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
               'last_visit' => 'nullable|date',
               'next_visit' => 'nullable|date',
          ]);

          $customer->update($validatedData);

          return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente.');
     }

     public function destroy(Customer $customer)
     {
          $customer->delete();

          return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente.');
     }
}
