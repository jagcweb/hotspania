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
            $userMessage = $request->input('message');
            
            // Buscar contenido relevante en la documentación
            $relevantDocs = $this->findRelevantDocumentation($userMessage);
            
            // Construir el contexto
            $context = $this->buildContext($relevantDocs);
            
            $messages = [
                [
                    'role' => 'system',
                    'content' => $this->getSystemPrompt($context)
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
                'sources' => $relevantDocs->pluck('title')->toArray()
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
            $context .= "=== {$doc->title} ===\n";
            $context .= substr($doc->content, 0, 500) . "...\n\n";
        }

        return $context;
    }

    private function getSystemPrompt($context)
    {
        return "Eres un asistente especializado en ayudar con consultas basándote en la documentación proporcionada.

INSTRUCCIONES:
1. Responde únicamente basándote en la documentación proporcionada
2. Si la información no está en la documentación, indica claramente que no tienes esa información
3. Sé preciso y útil en tus respuestas
4. Proporciona ejemplos cuando sea posible
5. Responde en español

DOCUMENTACIÓN DISPONIBLE:
{$context}

Responde de manera clara y profesional.";
    }
}