<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        // Instead of writing $this->authorize('delete', $image); for each method we can map the Resource routing to the Policy
        $this->authorizeResource(Image::class, 'image');
    }

    public function create()
    {
        return view('images.create');
    }

    public function store(StoreImageRequest $request)
    {
        try {
            $file = $request->file('image');
            $size = $file->getSize();
            $path = $file->storePublicly('uploads', 's3');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['image' => 'Storage service is unreachable.']);
        }

        if ($path === false) {
            return back()->withInput()
                ->withErrors(['image' => 'The file could not be uploaded to the storage server.']);
        }

        $image = $request->user()->images()->create([
            'title' => $request->title,
            'path' => $path,
            'size' => $size,
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Image \"$image->title\" uploaded");
    }

    public function destroy(Image $image)
    {
        Storage::disk('s3')->delete($image->path);
        $image->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', "Image \"$image->title\" deleted");
    }
}
