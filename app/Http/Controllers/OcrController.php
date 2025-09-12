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

        // Optionally, parse $fullText to extract fields (simple demo)
        $fields = [
            'firstname' => '',
            'middlename' => '',
            'lastname' => '',
            'birthdate' => '',
            'email' => '',
            'contact' => '',
            // Add more fields as needed
        ];

        // Extract first and middle name
        if (preg_match('/1\. NAME\s*([A-Z ]+)\s*\(Middle\)\s*([A-Z]+)\s*2\. SEX/i', $fullText, $matches)) {
            $fields['firstname'] = trim($matches[1]);
            $fields['middlename'] = trim($matches[2]);
        }

        // Extract last name (ONTE)
        if (preg_match('/\(18\)\s*([A-Z]+)\s*\(Month\)/i', $fullText, $matches)) {
            $fields['lastname'] = trim($matches[1]);
        }

        // Extract birthdate
        if (preg_match('/(\d{2} [A-Z]+ \d{4})/', $fullText, $matches)) {
            $fields['birthdate'] = $matches[1];
        }



        return response()->json([
            'raw_text' => $fullText,
            'fields' => $fields,
        ]);
    }
}