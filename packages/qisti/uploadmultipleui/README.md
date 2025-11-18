# qisti/uploadmultipleui

Blade-powered UI for uploading multiple files to your Laravel app. Ships with routes, controller logic, and publishable views/config so you can drop in a working upload screen or embed the form in your own page.

## Install (local path package)
1. Add a path repository that points to this package folder:
   ```json
   "repositories": [
     { "type": "path", "url": "packages/qisti/uploadmultipleui", "options": { "symlink": true } }
   ]
   ```
2. Require the package:
   ```bash
   composer require qisti/uploadmultipleui:"*"
   ```
3. (Optional) Publish and tweak config/views:
   ```bash
   php artisan vendor:publish --tag=uploadmultipleui-config
   php artisan vendor:publish --tag=uploadmultipleui-views
   ```
4. Make sure your storage disk is set up (e.g. `php artisan storage:link` if using the `public` disk).

## Usage
- Visit the built-in screen at `/upload-multiple-ui` (route prefix is configurable).
- Or embed the form in your own Blade using the component tag (`smart-upload` is the new alias; `qisti-multiupload` still works for backward compatibility):
  ```blade
  <x-smart-upload
      label="Supporting Document"
      name="files"
      accept=".doc,.docx,.xls,.xlsx,.zip,.pdf,.png,.jpg,.jpeg"
      :max-files="config('uploadmultipleui.max_files')"
      :max-size="config('uploadmultipleui.max_size')"
      wire-model="attachments" {{-- optional Livewire binding --}}
      input-attributes=""    {{-- e.g., 'wire:model.defer=\"attachments\"' --}}
      :required="false"      {{-- show * and add required attribute --}}
      badge="Optional text badge"
      {{-- set :max-files to 1 for single file, higher for multiple uploads --}}
      main-color="#1f3a70"   {{-- primary/link/icon color (default shown) --}}
      dropzone-color="#fafafa" {{-- drop area background --}}
      dropzone-border-color="#d4d4d8" {{-- drop area border --}}
      dropzone-active-color="#eef2ff"  {{-- drop area on drag-over/active --}}
  />
  ```
- To use it standalone, set `:standalone=\"true\"` and pass `action` (it will render its own form/CSRF). For Livewire, use `wire-model` or `input-attributes`.
- Handling uploads yourself? You can point to the controller actions:
  ```php
  Route::get('/my-upload', [\Qisti\UploadMultipleUi\Http\Controllers\UploadMultipleUiController::class, 'index']);
  Route::post('/my-upload', [\Qisti\UploadMultipleUi\Http\Controllers\UploadMultipleUiController::class, 'store']);
  ```
- Example controller snippet if you want to handle storage yourself:
  ```php
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Storage;

  public function store(Request $request)
  {
      $data = $request->validate([
          'files' => ['required', 'array', 'max:' . config('uploadmultipleui.max_files')],
          'files.*' => [
              'file',
              'max:' . config('uploadmultipleui.max_size'), // size in KB
              'mimes:doc,docx,xls,xlsx,zip,pdf,png,jpg,jpeg',
          ],
      ]);

      $disk = config('uploadmultipleui.disk', 'public');
      $path = trim(config('uploadmultipleui.path', 'uploads'), '/');
      $stored = [];

      foreach ($data['files'] as $file) {
          $stored[] = $file->store($path, $disk); // returns path/to/file.ext
      }

      return back()->with('uploaded', $stored);
  }
  ```

## Configuration
`config/uploadmultipleui.php` (publishable) exposes:
- `disk`: filesystem disk used for storage (default `public`)
- `path`: directory within the disk where files are stored (default `uploads`)
- `max_files`: total files allowed per request
- `max_size`: per-file size limit in kilobytes
- `middleware`: middleware stack applied to package routes
- `route_prefix`: URL prefix for the package routes

The included controller validates the limits, stores each file on the configured disk/path, and returns a success list of stored locations.
# smart-upload
