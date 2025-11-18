<?php

namespace Qisti\UploadMultipleUi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadMultipleUiController extends Controller
{
    public function index()
    {
        return view('uploadmultipleui::upload-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:' . config('uploadmultipleui.max_files')],
            'files.*' => ['file', 'max:' . config('uploadmultipleui.max_size')],
        ]);

        $disk = config('uploadmultipleui.disk');
        $path = config('uploadmultipleui.path');
        $storedPaths = [];

        foreach ($validated['files'] as $file) {
            $storedPaths[] = $file->store($path, $disk);
        }

        return back()->with('uploadmultipleui_uploaded', $storedPaths);
    }
}
