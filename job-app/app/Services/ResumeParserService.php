<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class ResumeParserService
{
    private GroqApiService $groqApi;

    public function __construct(GroqApiService $groqApi)
    {
        $this->groqApi = $groqApi;
    }

    /**
     * Parse a PDF resume and return structured fields.
     *
     * @param  UploadedFile $file  The uploaded PDF resume file.
     * @return array{
     *   contact_details: string,
     *   summary: string,
     *   skills: string,
     *   experience: string,
     *   education: string
     * }
     * @throws InvalidArgumentException  If text cannot be extracted from the PDF.
     * @throws RuntimeException          If the Groq API call fails.
     */
    public function parse(UploadedFile $file): array
    {
        $rawText = $this->extractTextFromPdf($file);
        $structured = $this->structureWithGroq($rawText);

        return $this->normalizeOutput($structured);
    }

    /**
     * Extract raw text from a PDF file in-memory.
     * PdfParser is instantiated lazily to avoid class-not-found errors
     * at service resolution time.
     */
    private function extractTextFromPdf(UploadedFile $file): string
    {
        try {
            // Lazy instantiation — avoids fatal error if package is missing
            $parserClass = 'Smalot\PdfParser\Parser';

            if (!class_exists($parserClass)) {
                throw new RuntimeException('PDF parser package (smalot/pdfparser) is not installed.');
            }

            $pdfParser = new $parserClass();
            $pdf = $pdfParser->parseFile($file->getRealPath());
            $text = $pdf->getText();
        } catch (RuntimeException $e) {
            throw $e; // Re-throw our own RuntimeException
        } catch (\Exception $e) {
            Log::error('PDF parsing failed', [
                'filename' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            throw new InvalidArgumentException(
                'Unable to extract text from this PDF. Please ensure the file is text-based, not a scan.'
            );
        }

        $text = trim($text);
        
        // Sanitize the text to ensure it's valid UTF-8, replacing invalid sequences
        // This prevents "json_encode error: Malformed UTF-8 characters" when sending to Groq
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        if (empty($text) || strlen($text) < 20) {
            throw new InvalidArgumentException(
                'This PDF appears to be empty or a scanned image. Please upload a text-based PDF resume.'
            );
        }

        return $text;
    }

    /**
     * Send raw text to Groq to extract structured resume data.
     *
     * @throws RuntimeException If the Groq API call fails.
     */
    private function structureWithGroq(string $rawText): array
    {
        $systemPrompt = <<<'PROMPT'
You are a resume parsing assistant. Extract structured information from the resume text provided. Respond with ONLY a valid JSON object containing these exact keys: contact_details, summary, skills, experience, education.

Rules:
- contact_details: JSON object with keys: name, email, phone, location
- skills: JSON array of strings
- experience: JSON array of objects with keys: title, company, duration, description
- education: JSON array of objects with keys: degree, institution, year
- summary: string paragraph summarizing the candidate's profile
- If a field is missing from the resume, use an empty array [] or empty string ""
- Do NOT include markdown, code fences, or any text outside the JSON object
PROMPT;

        return $this->groqApi->complete($systemPrompt, $rawText);
    }

    /**
     * Normalize the Groq output so every field is a JSON-encoded string
     * suitable for storage in the database.
     */
    private function normalizeOutput(array $data): array
    {
        return [
            'contact_details' => is_array($data['contact_details'] ?? null)
                ? json_encode($data['contact_details'])
                : json_encode(['name' => '', 'email' => '', 'phone' => '', 'location' => '']),
            'summary' => is_string($data['summary'] ?? null)
                ? $data['summary']
                : '',
            'skills' => is_array($data['skills'] ?? null)
                ? json_encode($data['skills'])
                : json_encode([]),
            'experience' => is_array($data['experience'] ?? null)
                ? json_encode($data['experience'])
                : json_encode([]),
            'education' => is_array($data['education'] ?? null)
                ? json_encode($data['education'])
                : json_encode([]),
        ];
    }
}
