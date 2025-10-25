<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\AnnotateImageRequest;

class OcrController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'ocr_file' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $file = $request->file('ocr_file');
        $filePath = $file->getRealPath();
        $mime = $file->getMimeType();

        $vision = new ImageAnnotatorClient([
            'credentials' => storage_path('app/google-vision-key.json'),
        ]);

        if ($mime === 'application/pdf') {
            $content = file_get_contents($filePath);
            $requests = [
                [
                    'inputConfig' => [
                        'content' => $content,
                        'mimeType' => 'application/pdf',
                    ],
                    'features' => [
                        ['type' => Feature\Type::DOCUMENT_TEXT_DETECTION]
                    ],
                ]
            ];
            $response = $vision->batchAnnotateFiles(['requests' => $requests]);
            $fullText = '';
            foreach ($response->getResponses() as $fileResponse) {
                foreach ($fileResponse->getResponses() as $imgResponse) {
                    $annotation = $imgResponse->getFullTextAnnotation();
                    if ($annotation) {
                        $fullText .= $annotation->getText();
                    }
                }
            }
        } else {
            $image = (new Image())->setContent(file_get_contents($filePath));
            $feature = (new Feature())->setType(Feature\Type::TEXT_DETECTION);

            $annotateImageRequest = new AnnotateImageRequest();
            $annotateImageRequest->setImage($image);
            $annotateImageRequest->setFeatures([$feature]);

            $batchRequest = new BatchAnnotateImagesRequest();
            $batchRequest->setRequests([$annotateImageRequest]);

            $response = $vision->batchAnnotateImages($batchRequest);
            $fullText = '';
            foreach ($response->getResponses() as $imgResponse) {
                $annotation = $imgResponse->getFullTextAnnotation();
                if ($annotation) {
                    $fullText .= $annotation->getText();
                }
            }
        }

        $fields = [
            'firstname' => '',
            'middlename' => '',
            'lastname' => '',
            'birthdate' => '',
            'email' => '',
            'contact' => '',
        ];

        // Extract names
        if (preg_match('/1\. NAME\s*([A-Z ]+)\s*\(Middle\)\s*([A-Z]+)\s*2\. SEX/i', $fullText, $matches)) {
            $fields['firstname'] = trim($matches[1]);
            $fields['middlename'] = trim($matches[2]);
        }
        if (preg_match('/\(18\)\s*([A-Z]+)\s*\(Month\)/i', $fullText, $matches)) {
            $fields['lastname'] = trim($matches[1]);
        }
        if (preg_match('/\(First\)\s*\n?([A-Z\- ]+)/i', $fullText, $matches)) {
            $fields['firstname'] = trim($matches[1]);
        }
        if (preg_match('/\(Middle\)\s*\n?([A-Z\- ]+)/i', $fullText, $matches)) {
            $fields['middlename'] = trim($matches[1]);
        }
        if (preg_match('/\(Last\)\s*\n?([A-Z\- ]+)/i', $fullText, $matches)) {
            $fields['lastname'] = trim($matches[1]);
        }
        // Try to match "LastName, FirstName MiddleName" (common printed format)
        if (preg_match('/([A-Z][A-Z\'\-]+),\s*([A-Z][A-Z\'\-]+)(?:\s+([A-Z][A-Z\'\-]+))?/i', $fullText, $m)) {
            // Only overwrite empty parts so earlier extraction rules remain if present
            if (empty($fields['lastname'])) {
                $fields['lastname'] = trim($m[1]);
            }
            if (empty($fields['firstname'])) {
                $fields['firstname'] = trim($m[2]);
            }
            if (!empty($m[3]) && empty($fields['middlename'])) {
                $fields['middlename'] = trim($m[3]);
            }
        }

        // Extract birthdate
        if (preg_match('/(\d{2} [A-Z]+ \d{4})/', $fullText, $matches)) {
            $fields['birthdate'] = date('Y-m-d', strtotime($matches[1]));

        }

        

        // // Build a single formatted name: "LastName, FirstName MiddleName"
        // $last = trim($fields['lastname'] ?? '');
        // $first = trim($fields['firstname'] ?? '');
        // $middle = trim($fields['middlename'] ?? '');

        // if ($last !== '') {
        //     $nameFormatted = $last;
        //     if ($first !== '') {
        //         $nameFormatted .= ', ' . $first;
        //         if ($middle !== '') {
        //             $nameFormatted .= ' ' . $middle;
        //         }
        //     }
        // } else {
        //     // Fallback: join whatever parts we have
        //     $parts = array_filter([$first, $middle, $last]);
        //     $nameFormatted = implode(' ', $parts);
        // }

        // $fields['name_formatted'] = $nameFormatted;

        return response()->json([
            'raw_text' => $fullText,
            'fields' => $fields,
        ]);
    }
}