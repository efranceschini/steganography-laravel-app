<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SteganographyEncodeApiRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;

class SteganographyApiController extends Controller
{
    const OP_ENCODE = 'encode';

    const OP_DECODE = 'decode';

    public function encode(SteganographyEncodeApiRequest $request)
    {
        // Get the image, as long as it belongs to the logged in user
        // $image = $request->user()->images()->findOrFail($request->image_id);
        $image = Image::findOrFail($request->image_id);

        // Download the file from the bucket to a temporary location
        $tempPath = tempnam(sys_get_temp_dir(), 'upload_');
        $contents = Storage::disk('s3')->get($image->path);
        file_put_contents($tempPath, $contents);

        // POST request to external API
        $endpoint = $this->endpoint($request->encoding, self::OP_ENCODE);
        $response = Http::attach(
            'image', file_get_contents($tempPath), basename($image->path)
        )->post($endpoint, [
            'message' => $request->message,
        ]);

        // Cleanup temporary file
        unlink($tempPath);

        // Handle API error, just forward the external API response
        if ($response->failed()) {
            return response()->json($response->json(), 502);
        }

        // Return the external API data as JSON
        return response($response->body(), 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="encoded.png"');
    }

    public function decode(Request $request): JsonResponse
    {
        // POST request to external API
        $endpoint = $this->endpoint($request->encoding, self::OP_DECODE);
        echo $endpoint;

        $response = Http::attach(
            'image',
            file_get_contents($request->file('image')->getRealPath()), // binary contents
            $request->file('image')->getClientOriginalName()        // original filename
        )->post($endpoint);

        // Return the external API data as JSON
        return response()->json($response->json(), $response->status());
    }

    private function endpoint(string $encoding, string $operation): string
    {
        $baseUrl = config('api.steganography.base_url');
        $endpoint = "$baseUrl/$encoding/$operation";

        return $endpoint;
    }
}
