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
- Or embed the form in your own Blade:
  ```blade
  @include('uploadmultipleui::upload-form')
  ```
- Handling uploads yourself? You can point to the controller actions:
  ```php
  Route::get('/my-upload', [\Qisti\UploadMultipleUi\Http\Controllers\UploadMultipleUiController::class, 'index']);
  Route::post('/my-upload', [\Qisti\UploadMultipleUi\Http\Controllers\UploadMultipleUiController::class, 'store']);
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
