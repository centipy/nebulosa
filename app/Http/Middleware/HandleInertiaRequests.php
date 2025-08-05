<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy; // ¡CORRECCIÓN AQUÍ: de Tightenco a Tighten!

class HandleInertiaRequests extends Middleware
{
     /**
      * The root template that's loaded with the Inertia.js response.
      *
      * @see https://inertiajs.com/server-side-setup#root-template
      * @var string
      */
     protected $rootView = 'app';

     /**
      * Determines the current asset version.
      *
      * @see https://inertiajs.com/server-side-setup#asset-version
      * @param  \Illuminate\Http\Request  $request
      * @return string|null
      */
     public function version(Request $request): ?string
     {
          return parent::version($request);
     }

     /**
      * Defines the props that are shared by default.
      *
      * @see https://inertiajs.com/shared-data#sharing-data
      * @param  \Illuminate\Http\Request  $request
      * @return array
      */
     public function share(Request $request): array
     {
          return array_merge(parent::share($request), [
               'auth' => [
                    'user' => $request->user() ? $request->user()->only('id', 'name', 'email') : null,
               ],
               'ziggy' => function () use ($request) {
                    return array_merge((new Ziggy)->toArray(), [
                         'location' => $request->url(),
                    ]);
               },
               // --- ¡AÑADE ESTAS LÍNEAS PARA COMPARTIR LOS MENSAJES FLASH! ---
               'flash' => [
                    'success' => fn() => $request->session()->get('success'),
                    'error' => fn() => $request->session()->get('error'),
                    'import_errors' => fn() => $request->session()->get('errors') ? $request->session()->get('errors')->get('import_errors') : null,
               ],
               // -----------------------------------------------------------
          ]);
     }
}
