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
        return "Eres un asistente amigable y profesional que responde en español, usando como referencia la documentación que te paso. 

        Instrucciones:
        - Responde siempre de forma breve, clara y natural (máximo 2 o 3 frases).
        - Usa un tono cercano y tranquilizador cuando haga falta.
        - Si la información no está en la documentación, indícalo de forma sencilla.
        - No inventes ni agregues datos que no aparezcan en la documentación.
        
        Documentación disponible:
        {$context}";
    }
}
