<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple Files</title>
</head>
<body style="background:#f8fafc; margin:0; padding:24px;">
    <div style="max-width:200px; margin:0 auto;">
        {{-- Demo of every configurable prop on the smart-upload component --}}
        <x-smart-upload
            label="Supporting Document" {{-- text shown above the dropzone --}}
            :max-files="config('uploadmultipleui.max_files')" {{-- cap total files; set to 1 for single upload --}}
            :max-size="config('uploadmultipleui.max_size')" {{-- per-file size limit in KB --}}
            accept=".doc,.docx,.xls,.xlsx,.zip,.pdf,.png,.jpg,.jpeg" {{-- allowed extensions --}}
            :standalone="true" {{-- renders its own form + CSRF --}}
            main-color="#0f766e" {{-- primary/link/icon color --}}
            dropzone-color="#ecfeff" {{-- drop area background --}}
            dropzone-border-color="#22d3ee" {{-- drop area border color --}}
            dropzone-active-color="#cffafe" {{-- drop area color on drag/active --}}
            required {{-- mark as required and show * --}}
        />
    </div>
</body>
</html>
