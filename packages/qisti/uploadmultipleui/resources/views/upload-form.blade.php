<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple Files</title>
    <style>
        :root { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { background: #f8fafc; margin: 0; padding: 2rem; }
        .card { max-width: 720px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08); }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .title { font-size: 1.25rem; font-weight: 700; color: #0f172a; }
        .muted { color: #475569; font-size: 0.95rem; margin: 0 0 16px; line-height: 1.5; }
        .field { border: 2px dashed #cbd5e1; border-radius: 12px; background: #f8fafc; padding: 30px 24px; text-align: center; transition: all 0.2s ease; }
        .field:hover { border-color: #6366f1; background: #eef2ff; }
        .field input[type="file"] { width: 100%; cursor: pointer; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 18px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; border-radius: 10px; color: #fff; font-weight: 600; cursor: pointer; box-shadow: 0 12px 30px rgba(99, 102, 241, 0.35); transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 16px 36px rgba(99, 102, 241, 0.4); }
        .alert { padding: 12px 14px; border-radius: 10px; margin-bottom: 14px; font-size: 0.95rem; }
        .alert-success { background: #ecfeff; color: #0f766e; border: 1px solid #99f6e4; }
        .alert-error { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        ul { padding-left: 18px; margin: 0; color: #0f172a; }
        li { margin-bottom: 6px; }
        code { background: #e2e8f0; padding: 2px 6px; border-radius: 6px; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div class="title">Upload Multiple Files</div>
        <span style="color:#6366f1;font-weight:700;">qisti/uploadmultipleui</span>
    </div>
    <p class="muted">
        Select several files and submit. Results will be stored on the configured disk (`{{ config('uploadmultipleui.disk') }}`)
        inside `{{ config('uploadmultipleui.path') }}`.
    </p>

    @if (session('uploadmultipleui_uploaded'))
        <div class="alert alert-success">
            Uploaded successfully:
            <ul>
                @foreach (session('uploadmultipleui_uploaded') as $file)
                    <li><code>{{ $file }}</code></li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Upload failed:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('uploadmultipleui.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="field">
            <input type="file" name="files[]" id="files" multiple>
            <p class="muted" style="margin: 8px 0 0;">
                Max files: {{ config('uploadmultipleui.max_files') }},
                Max size: {{ config('uploadmultipleui.max_size') }} KB each.
            </p>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:18px;">
            <button type="submit" class="btn">Upload</button>
        </div>
    </form>
</div>
</body>
</html>
