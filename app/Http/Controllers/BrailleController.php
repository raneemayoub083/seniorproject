<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;


class BrailleController extends Controller
{
    public function translate(Request $request)
    {
        Log::info('🔁 Braille translation requested', $request->all());

        $request->validate([
            'fileUrl' => 'required|string',
            'lessonTitle' => 'required|string',
        ]);

        try {
            $fileUrl = $request->fileUrl;
            $path = public_path(parse_url($fileUrl, PHP_URL_PATH));

            Log::info('📂 Reading file from path:', ['path' => $path]);

            if (!file_exists($path)) {
                return response()->json(['error' => 'File not found.'], 404);
            }

            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $text = '';

            if ($extension === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText();
            } else {
                $text = file_get_contents($path);
            }

            Log::info('📄 Extracted text length: ' . strlen($text));

            $braille = $this->convertToBraille(strip_tags($text));

            return response($braille)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="' . Str::slug($request->lessonTitle) . '_braille.txt"');
        } catch (\Exception $e) {
            Log::error('🔥 Exception during Braille translation', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred during translation.'], 500);
        }
    }

    private function convertToBraille($text)
    {
        $brailleMap = [
            'a' => '⠁',
            'b' => '⠃',
            'c' => '⠉',
            'd' => '⠙',
            'e' => '⠑',
            'f' => '⠋',
            'g' => '⠛',
            'h' => '⠓',
            'i' => '⠊',
            'j' => '⠚',
            'k' => '⠅',
            'l' => '⠇',
            'm' => '⠍',
            'n' => '⠝',
            'o' => '⠕',
            'p' => '⠏',
            'q' => '⠟',
            'r' => '⠗',
            's' => '⠎',
            't' => '⠞',
            'u' => '⠥',
            'v' => '⠧',
            'w' => '⠺',
            'x' => '⠭',
            'y' => '⠽',
            'z' => '⠵',
            ' ' => ' '
        ];

        $text = strtolower($text);
        $braille = '';

        foreach (str_split($text) as $char) {
            $braille .= $brailleMap[$char] ?? $char;
        }

        return $braille;
    }
}
