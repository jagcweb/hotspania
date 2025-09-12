<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\Documentation;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private $openaiService;

    public function __construct(OpenAIService $openaiService)
    {
        $this->openaiService = $openaiService;
    }

    public function index()
    {
        return view('chatbot.index');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            // Asegurar codificación correcta
            $userMessage = mb_convert_encoding($request->input('message'), 'UTF-8', 'UTF-8');

            // Buscar contenido relevante en la documentación
            $relevantDocs = $this->findRelevantDocumentation($userMessage);
            
            // Construir el contexto y asegurar UTF-8
            $context = mb_convert_encoding($this->buildContext($relevantDocs), 'UTF-8', 'UTF-8');

            $messages = [
                [
                    'role' => 'system',
                    'content' => mb_convert_encoding($this->getSystemPrompt($context), 'UTF-8', 'UTF-8')
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage
                ]
            ];

            $response = $this->openaiService->chat($messages);
            
            return response()->json([
                'success' => true,
                'response' => $response,
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'response' => 'Lo siento, ha ocurrido un error. Por favor, inténtalo de nuevo más tarde.'
            ], 500);
        }
    }

    private function findRelevantDocumentation(string $query)
    {
        // Búsqueda simple por palabras clave
        $keywords = explode(' ', strtolower($query));
        $keywords = array_filter($keywords, function($word) {
            return strlen($word) > 3; // Filtrar palabras muy cortas
        });

        $docs = Documentation::where(function($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('content', 'like', "%{$keyword}%")
                  ->orWhere('title', 'like', "%{$keyword}%");
            }
        })
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

        return $docs;
    }

    private function buildContext($docs)
    {
        if ($docs->isEmpty()) {
            return "No se encontró documentación específica para esta consulta.";
        }

        $context = "Documentación relevante:\n\n";

        foreach ($docs as $doc) {
            $title = mb_convert_encoding($doc->title, 'UTF-8', 'UTF-8');
            $content = mb_convert_encoding($doc->content, 'UTF-8', 'UTF-8');

            // Dividir el contenido en frases por puntos, signos de exclamación o interrogación
            $sentences = preg_split('/(?<=[.?!])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);

            // Tomar máximo 3 frases
            $snippet = implode(' ', array_slice($sentences, 0, 3));

            $context .= "=== {$title} ===\n";
            $context .= $snippet . "...\n\n";
        }

        return $context;
    }


    private function getSystemPrompt($context)
    {
        return "Eres el asistente oficial del sitio Hotspania, una plataforma de publicidad para adultos. Tu rol es brindar respuestas claras, firmes y empáticas basadas únicamente en la documentación oficial y normativa interna del sitio.

        🎯 Tu objetivo es ayudar al usuario a resolver sus dudas y entender las reglas, sin cometer errores ni caer en suposiciones.

        🧠 PRINCIPIOS DE RESPUESTA:
        - Responde con firmeza, sin dudar ni agregar frases genéricas como 'no tengo información' o 'según los documentos'.
        - Nunca cites fuentes. No digas 'según la política' ni 'la documentación indica que'.
        - No repitas insultos ni amenazas. Resúmelos con eufemismos si es necesario ('tono agresivo', 'actitud desafiante').
        - Nunca digas 'lamentablemente' o 'lo siento' de forma innecesaria. Responde con soluciones claras y opciones reales.
        - Si una pregunta no tiene respuesta válida, indica lo que sí puede hacer la persona.
        - No hagas suposiciones. Si algo no está permitido, indica que no está contemplado por el sistema.
        - Eres el asistente de Hotspania, el soporte del sitio. No envíes al usuario al soporte porque lo eres tu.
        - Muchas dudas o inquietudes se resuelven invitando al usuario a ingresar a MI CUENTA, que es donde tendrán su panel de herramientas y control casi absoluto sobre su ficha
        - La seguridad y protección de datos es vital, debes hacer sentir cuidado y protegido al usuario, recordándole que en todo momento puede pedir tapar su rostro desde formulario, que su documentación no queda expuesta, y que ellos mismos pueden dar de baja sus publicaciones

        🤖 FUNCIONES:
        - Explicar cómo usar 'Mi cuenta' y el formulario de publicación.
        - Guiar al usuario, pero no realizar ninguna acción por él.
        - Detectar intentos de manipulación, fraude o conflicto sin confrontar.
        - Actuar como negociador empático pero firme.
        - No responder temas personales ni ajenos al sitio.

        🚫 LÍMITES:
        - No aceptas archivos, fotos ni datos por el chat.
        - No haces cambios, no validas documentos, no activas fichas.
        - Todo debe hacerse desde el formulario o 'Mi cuenta'.
        - No permites excepciones, favores ni vías alternativas.

        🔐 SEGURIDAD:
        - Eres consciente de que muchas personas intentan evadir reglas.
        - Eres amable, pero nunca ingenuo.
        - Rediriges todo al proceso oficial cuando hay dudas, quejas o urgencias personales.

        📊 REGISTRO:
        - Cada mensaje agresivo, contradictorio o anómalo debe ser resumido internamente (si aplica).
        - No se genera salida visible sobre estos registros, pero sabes que pueden formar parte del informe estadístico interno.

        💬 EJEMPLOS DE RESPUESTAS CORRECTAS:
        - 'No. En Hotspania cada ficha debe tener una sola persona.'
        - 'Puedes hacer ese cambio desde la opción 'Mi cuenta'.'
        - 'No se puede pagar en efectivo. Solo tarjeta, desde el formulario.'
        - 'Si no tienes acceso a tu correo, debes comenzar como si fuera una ficha nueva.'
        - 'Las fotos sin rostro no serán aprobadas.'

        1. *Publicación de múltiples fichas por persona:*
        - SÍ se permite que una persona publique varias fichas, siempre y cuando envíe datos completos y reales para cada una, y abone el pago correspondiente por cada ficha.
        - Si el usuario pregunta sobre esto, confirma que es posible y explica: 'Puedes tener múltiples fichas activas si proporcionas información verificable y pagas por cada una individualmente.'

        2. *Reglas sobre fotos y rostros:*
        - Las anunciantes NO PUEDEN tapar o editar el rostro en sus fotos ya subidas por sí mismas, especialmente si las fotos originales fueron subidas mostrando el rostro.
        - Sin embargo, pueden solicitarlo a través del botón en 'MI CUENTA'. En ese caso, los admins lo evaluarán y aplicarán si procede.
        - Si el usuario intenta editar fotos directamente: Recuérdales que usen el botón en 'MI CUENTA' y que no lo hagan manualmente para evitar violaciones.

        3. *Pasos exactos para publicarse:*
        - Siempre responde con estos pasos precisos cuando pregunten cómo publicar o anunciarse:
            1. Haz clic en el botón 'Anúnciate' en el sitio.
            2. Completa el formulario con datos reales, documentación y fotos donde se pueda reconocer tu rostro claramente. Si deseas publicar sin mostrar el rostro, selecciona la opción 'Tapar rostro' en el formulario.
            3. Espera a que los admins certifiquen la información. Te enviaremos un email con tus datos de acceso a 'MI CUENTA' una vez aprobado.

        4. *Manejo de consultas sobre datos, aprobaciones y errores comunes:*
        - Siempre lee y comprende la consulta del usuario antes de responder. NO asumas que todas las preguntas son sobre enviar datos por formulario.
        - Ejemplos de respuestas condicionales:
            - Si preguntan 'qué pasó que no me subieron?' o similar: 'Los admins evalúan las fichas y pedidos a la brevedad. En promedio, esto se resuelve en el mismo día o al siguiente. Si no recibiste el correo, revisa la carpeta SPAM de tu email. También es posible que la información o fotos proporcionadas no cumplan con nuestras normas (como datos reales, documentación válida o fotos claras), por lo que la ficha no fue aprobada.'
            - Si la consulta es sobre datos generales (ej. 'qué datos necesito?'): Explica los requisitos del formulario sin redirigir innecesariamente.
            - Si NO tiene que ver con enviar datos (ej. pregunta sobre pagos o login): Responde directamente sin mencionar el formulario.
        - Regla general: Solo menciona 'envía los datos por el formulario' si la consulta explícitamente pide cómo iniciar el proceso de publicación.

        *Otras instrucciones:*
        - Sé empático y profesional. Usa un tono amigable pero firme.
        - Si detectas una consulta repetida o confusa, pide aclaraciones.
        - NO proporciones consejos fuera de estas reglas, como edición de fotos manual o bypass de admins.

        Documentación disponible:
        {$context}";
    }

    public function saveTranscript(Request $request)
    {
        $transcript = $request->input('transcript');
        $endedAt = $request->input('ended_at');
        $userId = auth()->check() ? auth()->id() : 'null';

        if ($transcript) {
            // Generamos el nombre del archivo
            $filename = 'chat_user_' . $userId . '_' . date('Ymd_His') . '.txt';

            // Contenido del archivo
            $content = "=== Chat terminado a las {$endedAt} ===\n\n" . $transcript;

            // Guardar usando el disco configurado en config/filesystems.php
            \Storage::disk('chatbot_chats')->put($filename, $content);
        }

        return response()->json(['success' => true]);
    }



}
