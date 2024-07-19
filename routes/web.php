<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/api/autowebs_clientes', function () {
    return DB::table('autowebs_clientes')->get();
});

Route::get('/api/autowebs_negocios', function () {
    return DB::table('autowebs_negocios')->get();
});

Route::get('/api/autowebs_negocios/{numero}', function ($numero) {
    $check =  DB::table('autowebs_negocios')->where('telefono' , $numero);

    if(!$check->exists()) return response()->json(['respuesta' => 404 ]);

    return $check->first();
    
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
