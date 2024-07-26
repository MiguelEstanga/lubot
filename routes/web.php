<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
Route::get('/api/autowebs_clientes', function () {
    return DB::table('autowebs_clientes')->get();
});

Route::get('/api/autowebs_negocios', function () {
    return DB::table('autowebs_negocios')->get();
});
Route::get('/api/testBot', function () {
      // Ruta completa al ejecutable de Python
      $workingDirectory = 'C:/Users/user/Desktop/lubot_bot/lubot';
        
      // Ruta completa al ejecutable de Python
      $pythonPath = 'C:/Users/user/AppData/Local/Programs/Python/Python313/python.exe';
      
      // Nombre del script de Python
      $scriptName = 'whatsapp.py';
      // Construir el comando
      $command = "cd $workingDirectory && $pythonPath $scriptName";
      // Ejecutar el comando y capturar la salida
       $output = shell_exec($command . ' 2>&1'); // Re
      return response()->json([
        'output' => $output,
        'mensage' => 'ejecutando bot'
      ]);
});

Route::get('/api/activar_bot', function () {
    //return 'aqui vamos';
    $response = Http::withHeaders(['Accept' => 'application/json'])
    ->get('https://0e40-190-120-248-131.ngrok-free.app/api/testBot');
    $clientes = $response->body();
});

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
