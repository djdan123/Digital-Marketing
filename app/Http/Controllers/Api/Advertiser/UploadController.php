<?php

namespace App\Http\Controllers\Api\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'type' => 'required|in:image,audio,video',
        ]);

        $rules = match ($request->type) {
            'image' => 'mimes:jpg,jpeg,png,webp,gif|max:5120',      // 5 Mo
            'audio' => 'mimes:mp3,wav,ogg,mpeg|max:20480',          // 20 Mo
            'video' => 'mimes:mp4,webm,mov,quicktime|max:102400',   // 100 Mo
        };

        $request->validate(['file' => $rules]);

        $file = $request->file('file');
        $folder = 'uploads/' . $request->type . 's/' . date('Y/m');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs($folder, $filename, 'public');

        return response()->json([
            'data' => [
                'path'     => $path,
                'url'      => Storage::disk('public')->url($path),
                'filename' => $file->getClientOriginalName(),
                'size'     => $file->getSize(),
                'mime'     => $file->getMimeType(),
                'type'     => $request->type,
            ]
        ], 201);
    }
}