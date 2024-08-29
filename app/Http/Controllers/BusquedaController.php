<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function likeCiudadad($ciudad)
    {
        // Convertir la entrada a minúsculas para la comparación insensible a mayúsculas
        $input = strtolower($ciudad);

        // Realizar la consulta utilizando LIKE, agregando los comodines %
        $ciudade = DB::table('ciudades')
            ->whereRaw('LOWER(nombre) LIKE ?', ["%{$input}%"])
            ->first();

        return $ciudade;
    }

    public function likePais($ciudad)
    {
        // Convertir la entrada a minúsculas para la comparación insensible a mayúsculas
        $input = strtolower($ciudad);

        // Realizar la consulta utilizando LIKE, agregando los comodines %
        $ciudade = DB::table('paises')
            ->whereRaw('LOWER(nombre) LIKE ?', ["%{$input}%"])
            ->first();

        return $ciudade;
    }

    public function likeBarrio($ciudad)
    {
        // Convertir la entrada a minúsculas para la comparación insensible a mayúsculas
        $input = strtolower($ciudad);

        // Realizar la consulta utilizando LIKE, agregando los comodines %
        $ciudade = DB::table('barrios')
            ->whereRaw('LOWER(nombre) LIKE ?', ["%{$input}%"])
            ->first();

        return $ciudade;
    }

    public function likeNegocio($ciudad)
    {
        // Convertir la entrada a minúsculas para la comparación insensible a mayúsculas
        $input = strtolower($ciudad);

        // Realizar la consulta utilizando LIKE, agregando los comodines %
        $ciudade = DB::table('tipos_negocio')
            ->whereRaw('LOWER(nombre) LIKE ?', ["%{$input}%"])
            ->first();

        return $ciudade;
    }
}
