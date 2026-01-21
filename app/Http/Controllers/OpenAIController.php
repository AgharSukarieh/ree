<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIFormFillerService;
use Illuminate\Support\Facades\Log;

class OpenAIController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIFormFillerService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * Fill form using OpenAI API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fillForm(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10|max:5000'
        ]);

        try {
            $prompt = $request->input('prompt');
            
            Log::info('Received form fill request', [
                'prompt_length' => strlen($prompt)
            ]);

            $filledData = $this->openAIService->fillForm($prompt);

            if ($filledData) {
                // Print to console (Laravel log)
                Log::info('=== OpenAI Form Fill Response ===');
                Log::info(json_encode($filledData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                
                // Store data in session for display page
                session(['openai_response' => $filledData]);
                session(['openai_prompt' => $prompt]);
                
                return response()->json([
                    'success' => true,
                    'data' => $filledData,
                    'redirect_url' => route('openai.response'),
                    'message' => 'تم تعبئة الفورم بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في تعبئة الفورم. يرجى المحاولة مرة أخرى.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error in fillForm controller', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display OpenAI response page
     * 
     * @return \Illuminate\View\View
     */
    public function showResponse()
    {
        $data = session('openai_response');
        $prompt = session('openai_prompt');

        if (!$data) {
            return redirect()->route('register')->with('error', 'لا توجد بيانات لعرضها');
        }

        // Clear session after displaying
        session()->forget(['openai_response', 'openai_prompt']);

        return view('openai-response', [
            'data' => $data,
            'prompt' => $prompt
        ]);
    }
}

