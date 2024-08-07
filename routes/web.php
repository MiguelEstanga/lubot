<?php

use App\Http\Controllers\LubotActiveWs ;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


Route::get('/', function () {
    return view('welcome');
});

// este controlador activa el bot 
Route::get('/api/activar_bot', function () {
    //return 'aqui vamos';
    $response = Http::withHeaders(['Accept' => 'application/json'])
    ->get('https://0e40-190-120-248-131.ngrok-free.app/api/testBot');
    $clientes = $response->body();
});

//este controloador returno los clientes 
Route::get('/api/autowebs_clientes', function () {
    return DB::table('autowebs_clientes')->get();
});

Route::get('/api/autowebs_negocios', function () {
    return DB::table('autowebs_negocios')->get();
});

//returna el negocio que a pasado las condiciones 
Route::get('/api/autowebs_negocios/{numero}', function ($numero) {
    $check =  DB::table('autowebs_negocios')->where('telefono' , $numero);

    if(!$check->exists()) return response()->json(['respuesta' => 404 ]);

    $empresa = $check->first();
    $data = array();

    $pais = DB::table('paises')->where('id' , $empresa->pais_id )->first();
    $ciudad = DB::table('ciudades')->where('id' , $empresa->ciudad_id )->first();
    $barrio = DB::table('barrios')->where('id' , $empresa->barrio_id )->first();
    $negocio = DB::table('tipos_negocio')->where('id' , $empresa->tipo_negocio_id )->first();

    $data['pais'] = $pais->nombre ?? null;
    $data['ciudad'] = $ciudad->nombre ?? null;
    $data['barrio'] = $barrio->nombre ?? null;
    $data['tipos_negocio'] = $negocio->nombre ?? null;
    $data['empresa'] = $empresa;

    return $data;
});

//test de prueba para probar la logica de los clientes que pasan el filtro 
Route::get('api/test' , function(){
    $clientes = DB::table('autowebs_clientes')->get();

    $clientes_autorizado = [];

    foreach ($clientes as $cliente)
    {
        if($cliente->ws === 1 && $cliente->bot_finalizado === 1 && $cliente->valid === 1 )
        {
            $check =  DB::table('autowebs_negocios')->where('telefono' , $cliente->telefono);
            if($check->exists()) $clientes_autorizado[] = $check->first();
        }
      }

    return $clientes_autorizado;
});

//returna los paises de lubot master
Route::get('/api/paises' , function () {
    $paises = DB::table('paises')->get();
    return $paises;
});

//returna los barrios de lubot master
Route::get('/api/barrios' , function () {
    $paises = DB::table('barrios')->get();
    return $paises;
});

//returna los negocion de lubot master
Route::get('/api/tipos_negocios' , function () {
    $paises = DB::table('tipos_negocio')->get();
    return $paises;
});

//returna las ciudades de lubot master
Route::get('/api/ciudades' , function () {
    $paises = DB::table('ciudades')->get();
    return $paises;
});

Route::get('api/activar_ws/{company_id}', [LubotActiveWs::class , 'iniciar_sesion_whatsapp_ws'] );

// rutas controladores para el login 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
