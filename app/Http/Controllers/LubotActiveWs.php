<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LubotActiveWs extends Controller
{
    public function scriptActivacion($company_id, $type)
    {
        $workingDirectory = 'C:\xampp\htdocs\lubot';
        // Ruta al ejecutable de Python y al script
        $pythonPath = 'C:/Program Files/Python312/python.exe';
        $scriptPath = 'C:\xampp\htdocs\lubot\iniciar_sesion.py';
        $command = "start powershell.exe -NoExit -Command \"cd '$workingDirectory'; & '$pythonPath' '$scriptPath' --company_id $company_id --type $type\"";
        // Ejecutar el comando en segundo plano
        pclose(popen($command, 'r'));
        // Opcionalmente, puedes devolver una respuesta al navegador
        echo "PowerShell iniciado y ejecutando el script de Python en $workingDirectory.";
    }

    function iniciar_sesion_whatsapp_ws($company_id)
    { 
        $workingDirectory = env('RUTA_ARCHIVO_PY'); // Ruta por defecto del archivo
        $pythonPath = env('PYTHON_PATH'); // Ejecutor
        $scriptPath = $workingDirectory . '/iniciar_sesion.py'; 
    
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Comando para Windows
            $command = "start powershell.exe -NoExit -Command \"cd '$workingDirectory'; & '$pythonPath' '$scriptPath' ";
        } else {
            // Comando para Unix/Linux
            $command = "gnome-terminal -- bash -c \"cd '$workingDirectory'; '$pythonPath' '$scriptPath' --company_id $company_id --type ws; exec bash\"";
        }
    
        // Ejecutar el comando en segundo plano
        pclose(popen($command, 'r'));
    
        // Opcionalmente, puedes devolver una respuesta al navegador
        echo "Terminal iniciada y ejecutando el script de Python en $workingDirectory.";
        return json_encode(['response' => 1]);
    }
   
}
