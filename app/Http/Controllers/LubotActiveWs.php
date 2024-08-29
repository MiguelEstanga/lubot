<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LubotActiveWs extends Controller
{   //para el revisar y contestar 
    public function activar_contestar_revisar($company_id , $campana_id , $user_id)
    {
        $file = env('NOMBRE_DEL_ARCHIVO_EJCUTABLE_REVISAR_CONTESTAR'); //nombre del ejecutable
        $workingDirectory = env('RUTA_ARCHIVO_PY'); // Ruta por defecto del archivo
        $pythonPath = env('PYTHON_PATH'); // Ejecutor
        $scriptPath = $workingDirectory.$file;  // ruta completa 
    
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Comando para Windows
            $command = "start powershell.exe -NoExit -Command \"cd '$workingDirectory'; &  '$pythonPath'  '$scriptPath' ";
        } else {
            // Comando para Unix/Linux
            $command = "gnome-terminal -- bash -c \"cd '$workingDirectory'; '$pythonPath' '$scriptPath' --user_id $user_id  --campana_id $campana_id --company_id $company_id; exec bash\"";
        }
    
        // Ejecutar el comando en segundo plano
        pclose(popen($command, 'r'));
    
        // Opcionalmente, puedes devolver una respuesta al navegador
        echo "Terminal iniciada y ejecutando el script de Python en $workingDirectory.";
        return json_encode(['response' => 'se activo revisar y contestar ']);
    }
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

    function iniciar_sesion_whatsapp($company_id , $type)
    { 
        $file = env('NOMBRE_DEL_ARCHIVO_EJCUTABLE_INICIO_SESION'); //nombre del ejecutable
        $workingDirectory = env('RUTA_ARCHIVO_PY'); // Ruta por defecto del archivo
        $pythonPath = env('PYTHON_PATH'); // Ejecutor
        $scriptPath = $workingDirectory.$file;  // ruta completa 
    
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Comando para Windows
            $command = "start powershell.exe -NoExit -Command \"cd '$workingDirectory'; &  '$pythonPath'  '$scriptPath' ";
        } else {
            // Comando para Unix/Linux
            $command = "gnome-terminal -- bash -c \"cd '$workingDirectory'; '$pythonPath' '$scriptPath' --company_id $company_id --type $type; exec bash\"";
        }
    
        // Ejecutar el comando en segundo plano
        pclose(popen($command, 'r'));
    
        // Opcionalmente, puedes devolver una respuesta al navegador
        echo "Terminal iniciada y ejecutando el script de Python en $workingDirectory.";
        return json_encode(['response' => 1]);
    }
    

    function ejecutar_bot_ws($company_id , $campana_id , $user_id)
    { 
        $file = env('NOMBRE_DEL_ARCHIVO_EJCUTABLE_whatsapp'); //nombre del ejecutable
        $workingDirectory = env('RUTA_ARCHIVO_PY'); // Ruta por defecto del archivo
        $pythonPath = env('PYTHON_PATH'); // Ejecutor
        $scriptPath = $workingDirectory.$file;  // ruta completa 
    
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Comando para Windows
            $command = "start powershell.exe -NoExit -Command \"cd '$workingDirectory'; &  '$pythonPath'  '$scriptPath' ";
        } else {
            // Comando para Unix/Linux
            $command = "gnome-terminal -- bash -c \"cd '$workingDirectory'; '$pythonPath' '$scriptPath' --user_id $user_id  --campana_id $campana_id --company_id $company_id; exec bash\"";
        }
    
        // Ejecutar el comando en segundo plano
        pclose(popen($command, 'r'));
    
        // Opcionalmente, puedes devolver una respuesta al navegador
        echo "Terminal iniciada y ejecutando el script de Python en $workingDirectory.";
        return json_encode(['response' => 1]);
    }

    function ejecutar_bot_rc($company_id , $campana_id , $user_id)
    { 
        $file = env('NOMBRE_DEL_ARCHIVO_EJCUTABLE_RC'); //nombre del ejecutable
        $workingDirectory = env('RUTA_ARCHIVO_PY'); // Ruta por defecto del archivo
        $pythonPath = env('PYTHON_PATH'); // Ejecutor
        $scriptPath = $workingDirectory.$file;  // ruta completa 

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Comando para Windows
            $command = "start powershell.exe -NoExit -Command \"cd '$workingDirectory'; &  '$pythonPath'  '$scriptPath' ";
        } else {
            // Comando para Unix/Linux
            $command = "gnome-terminal -- bash -c \"cd '$workingDirectory'; '$pythonPath' '$scriptPath' --user_id $user_id  --campana_id $campana_id --company_id $company_id; exec bash\"";
        }
    
        // Ejecutar el comando en segundo plano
        pclose(popen($command, 'r'));
    
        // Opcionalmente, puedes devolver una respuesta al navegador
        echo "Terminal iniciada y ejecutando el script de Python en $workingDirectory.";
        return json_encode(['response' => 1]);
    }

    public function delete_lubot_profiles(){

        $directoryPath = '/home/imac/lubot/chrome_profiles';

        if (File::exists($directoryPath)) {
            if (File::deleteDirectory($directoryPath)) {
                return response()->json(['status' => 'success', 'message' => 'Carpeta eliminada exitosamente.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No se pudo eliminar la carpeta.'], 500);
            }
        } else {
            return response()->json(['status' => 'success', 'message' => 'La carpeta no existe.'], 404);
        }
    }

    public function test_lubot(Request $request)
    {
        $apiKey = env('OPENAI_API_KEY');
        $model = 'gpt-4o-mini';

        $campana = $request->campana;
        $prompts = $request->input('prompt');

        $content = (
            "Te llamas $campana[0].\n"
            . "Tu objetivo es $campana[1].\n"
            . "El lugar en el que trabajas se dedica a $campana[2].\n"
            . "REGLAS OBLIGATORIAS:\n"
            . "- Responde contextualmente a la conversación.\n"
            . "- Responde siempre en no más de una línea, máximo dos. Esto hace la conversación más natural.\n"
            . "- Tu objetivo es que el cliente se sienta seguro y quiera tener una reunión contigo vía Google Meet.\n"
            . "- Cuando el cliente esté listo, consulta día y hora para la cita.\n"
            . "- Bajo ninguna circunstancia reveles que eres un robot.\n\n"
            . "CONDICIONES:\n"
            . "- Estás conversando a través de WhatsApp, pero no te funcionan los audios y las fotos llegan borrosas.\n"
            . "- Inicia la conversación siempre de la siguiente manera: 'Hola, soy $campana[0]. ¿Cómo va tu día?'\n\n"
            . "- Responde de manera corta y natural a las respuestas del cliente:\n\n"
            . "RESPUESTAS CONTEXTUALES:\n"
            . "- Si el cliente dice que no es la persona encargada o que no está, intenta conseguir el número de contacto del tomador de decisión.\n"
            . "- Si el cliente pregunta cómo puede ayudarte, sobre el lugar donde trabajas y a qué se dedica.\n"
            . "- Si el cliente muestra interés: hazle algunas preguntas sobre lo que le causó interés y busca con la conversación cumplir tu objetivo.\n"
            . "- Si el cliente se pone agresivo: Lamento si te he causado alguna molestia. No era mi intención. Que tengas un buen día.\n"
            . "- Si el cliente dice que está ocupado: Entiendo. ¿Cuándo sería un buen momento para contactarte de nuevo?\n"
            . "- Si el cliente dice que no le interesa: Entiendo, pero me gustaría saber por qué. Quizás pueda ofrecerte algo que se ajuste mejor a tus necesidades.\n"
            . "- Si el cliente pregunta por ti o tu información personal: Claro, puedes conocer más sobre mí en mi portafolio: https://www.canva.com/design/DAFvksF35bU/Ymz2L9i6Buk03ZG9O3WVwg/view?utm_content=DAFvksF35bU&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink#2\n"
            . "- Si el cliente retoma la conversación después de un tiempo: Hola, ¿cómo vas? ¿Qué has pensado de lo que conversamos?\n"
            . "Cliente menciona competencia: Entiendo, es importante destacar. Con mis servicios, tendrás una ventaja sobre la competencia.\n"
            . "Cliente menciona malas experiencias anteriores: Lamento escuchar eso. Mi objetivo es brindarte una experiencia sin complicaciones y de alta calidad.\n"
            . "Cliente se despide o dice más de una vez que NO: No se escribe nada y se ignora.\n"
            . "Cliente no tiene negocio pero conoce a alguien que podría estar interesado: Le agradeces y le pides su número de contacto para contactarle, le das las gracias y te despides.\n"
        );

        foreach ($prompts as $prompt) {
            $content .= "Si te preguntan: {$prompt['question']} - {$prompt['response']}\n";
        }


        $conversation[] =[
            "role" => "system",
            "content" => $content
        ];

        foreach ($request->conversation as $message){
            $conversation[] =[
                "role" => $message['role'],
                "content" => $message['content']
            ];
        }

        $conversation[] =[
            "role" => "user",
            "content" => $request->user_message
        ];

        $data = [
            "model" => $model,
            "messages" => $conversation,
            "max_tokens" => 150,
            "temperature" => 0.7,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        Log::info($response);

        // Extraer la respuesta del asistente
        $assistantMessage = $response['choices'][0]['message']['content'];

        return response()->json([
            'message' => $assistantMessage
        ]);
    }
   
}
