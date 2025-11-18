{{-- Resolve props and defaults (component is meant to live inside your form) --}}
@php
    $label = $label ?? 'Supporting Document';                            // heading text
    $name = $name ?? 'files';                                            // base input name
    $accept = $accept ?? '.doc,.docx,.xls,.xlsx,.zip,.pdf,.png,.jpg,.jpeg'; // accepted extensions (csv string)
    $acceptList = array_values(array_filter(array_map('trim', explode(',', $accept)))); // array value of allow-list for other logic
    $maxFiles = $maxFiles ?? config('uploadmultipleui.max_files');       // total file
    $maxSize = $maxSize ?? config('uploadmultipleui.max_size');          // size cap per file in KB
    $wireModel = $wireModel ?? null;                                     // optional Livewire binding
    $inputAttributes = $inputAttributes ?? '';                           // passthrough attributes
    $required = (bool) ($required ?? false);                             // mark required
    $maxFiles = (int) $maxFiles;                                         // normalize
    $mainColor = $mainColor ?? '#1f3a70';                                // theme color: icons/links
    $dropzoneColor = $dropzoneColor ?? '#fafafa';                        // dropzone background
    $dropzoneBorderColor = $dropzoneBorderColor ?? '#d4d4d8';            // dropzone border
    $dropzoneActiveColor = $dropzoneActiveColor ?? '#eef2ff';            // dropzone active/hover
    $id = $id ?? uniqid('umui-');                                        // id used for script scoping

    // Derive name [] and multiple attribute from file count limit.
    $effectiveMaxFiles = $maxFiles > 0 ? $maxFiles : config('uploadmultipleui.max_files');
    $allowsMultiple = $effectiveMaxFiles > 1;
    $fieldName = $allowsMultiple
        ? (str_ends_with($name, '[]') ? $name : $name.'[]')
        : $name;
@endphp

<style>
    .umui-force-full { width:100% !important; max-width:100% !important; min-width:0 !important; box-sizing:border-box !important; }
    .umui-form { width: 100%; max-width: 100%; min-width: 0; }
    .umui-header { display:flex; align-items:center; gap: 10px; }
    .umui-label { font-weight: 700; font-size: 1.05rem; color: #1f2937; overflow-wrap: anywhere; }
    .umui-required { color: #b91c1c; margin-left: 4px; font-weight: 800; }
    .umui-badge { background: #fef3c7; color: #92400e; padding: 6px 10px; border-radius: 999px; font-weight: 700; font-size: .9rem; display:inline-flex; align-items:center; gap:6px; }
    .umui-drop { border: 2px dashed var(--umui-drop-border); background: var(--umui-drop-bg); border-radius: 16px; padding: 28px; text-align:center; transition: all .2s ease; width: 100%; max-width: 100%; min-width: 0; inline-size: 100%; max-inline-size: 100%; box-sizing: border-box; overflow: hidden; display: block; flex: 1 1 auto; }
    .umui-drop.is-drag { border-color: var(--umui-drop-active); background: var(--umui-drop-active-bg); }
    .umui-drop.has-error { border-color: #b91c1c; background: #fef2f2; }
    .umui-icon-circle { width: 52px; height: 52px; border-radius: 999px; border: 2px solid var(--umui-primary); display:flex; align-items:center; justify-content:center; margin: 0 auto 12px; color: var(--umui-primary); }
    .umui-title { font-size: 1.1rem; font-weight: 700; color: #3f3f46; margin-bottom: 4px; overflow-wrap: anywhere; text-wrap: balance; }
    .umui-sub { color: #9ca3af; font-weight: 600; margin-bottom: 6px; overflow-wrap: anywhere; word-break: break-word; white-space: normal; }
    .umui-accept { color: #9ca3af; font-size: .95rem; margin-bottom: 12px; overflow-wrap: anywhere; word-break: break-all; white-space: normal; }
    .umui-browse { color: var(--umui-primary); font-weight: 800; text-decoration: none; font-size: 1.02rem; }
    .umui-list { margin-top: 18px; display: flex; flex-direction: column; gap: 12px; width: 100%; max-width: 100%; min-width: 0; inline-size: 100%; max-inline-size: 100%; flex: 1 1 auto; }
    .umui-item { border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px; background: #fff; display: grid; grid-template-columns: auto 1fr; gap: 12px; align-items: flex-start; box-shadow: 0 6px 18px rgba(0,0,0,0.02); width: 100%; max-width: 100%; min-width: 0; inline-size: 100%; max-inline-size: 100%; box-sizing: border-box; overflow: hidden; flex: 1 1 auto; }
    .umui-item-icon { width: 48px; height: 48px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; }
    .umui-item-body { min-width: 0; max-width: 100%; width: 100%; overflow: hidden; }
    .umui-item-name { font-weight: 800; color: #111827; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; width: 100%; }
    .umui-item-size { color: #9ca3af; font-weight: 700; font-size: .95rem; }
    .umui-actions { display:flex; gap: 12px; margin-top: 6px; flex-wrap: wrap; }
    .umui-link { font-weight: 800; font-size: 1rem; text-decoration: none; }
    .umui-link.blue { color: var(--umui-primary); }
    .umui-link.red { color: #ef4444; }
    .umui-error { margin-top: 10px; color: #b91c1c; font-weight: 600; }
    @media (max-width: 640px) {
        .umui-item { flex-direction: column; }
        .umui-actions { width: 100%; }
        .umui-item-name { white-space: normal; word-break: break-word; }
    }
</style>

{{-- Container sets theming variables and exposes limits via data-* --}}
<div
    class="umui-container umui-force-full"
    data-umui
    data-max-files="{{ $effectiveMaxFiles }}"
    data-allow-multiple="{{ $allowsMultiple ? 'true' : 'false' }}"
    id="{{ $id }}"
    style="
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
        --umui-primary: {{ $mainColor }};
        --umui-drop-bg: {{ $dropzoneColor }};
        --umui-drop-border: {{ $dropzoneBorderColor }};
        --umui-drop-active: {{ $dropzoneActiveColor }};
        --umui-drop-active-bg: {{ $dropzoneActiveColor }};
    ">
    {{-- Title + badge --}}
    <div class="umui-header">
        <div class="umui-label">
            {{ $label }}
            @if($required)
                <span class="umui-required">*</span>
            @endif
        </div>
    </div>

    {{-- Drop area and file input trigger --}}
    <div class="umui-drop umui-force-full" data-umui-drop style="width:100%;max-width:100%;min-width:0;">
        <div class="umui-icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24">
                <path d="M12 15.5V5m0 0 4 4M12 5l-4 4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
                <path d="M6.5 11a5.5 5.5 0 1 0 2.735 10.34l.052-.024A5.5 5.5 0 0 0 17.5 11" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
            </svg>
        </div>
        <div class="umui-title">Drag and drop to upload file</div>
        <div class="umui-sub">Maximum upload file size is {{ $maxSize / 1024 }} MB per upload.</div>
        <div class="umui-accept">Accepted: {{ $accept }}</div>
        <label class="umui-browse">
            <input
                type="file"
                name="{{ $fieldName }}"
                accept="{{ $accept }}"
                @if($allowsMultiple) multiple @endif
                class="hidden"
                data-umui-input
                {!! $required ? 'required' : '' !!}
                {!! $wireModel ? 'wire:model="'.$wireModel.'"' : '' !!}
                {!! $inputAttributes !!}>
            Browse files
        </label>
    </div>

    <div class="umui-error" data-umui-error></div>
    <div class="umui-list umui-force-full" data-umui-list></div>
</div>

<script>
(() => {
    // Cache DOM nodes and derive limits/state.
    const container = document.getElementById(@json($id));
    if (!container) return;

    const dropArea = container.querySelector('[data-umui-drop]');               // drop target
    const input = container.querySelector('[data-umui-input]');                 // hidden file input
    const list = container.querySelector('[data-umui-list]');                   // list container
    const errorEl = container.querySelector('[data-umui-error]');               // inline error slot
    const allowMultiple = container.dataset.allowMultiple === 'true';           // derived from max-files
    const maxFiles = parseInt(container.dataset.maxFiles, 10) || (allowMultiple ? 10 : 1); // per instance cap
    const dt = new DataTransfer();                                              // holds selected files
    const maxSizeKb = parseInt(@json($maxSize), 10) || 0;                       // size limit in KB
    const acceptList = @json($acceptList);                                      // allowed extensions
    const maxLabel = maxFiles === 1 ? 'file' : 'files';                         // for messages
    const showError = (message) => {
        errorEl.textContent = message || '';
        dropArea.classList.toggle('has-error', !!message);
    };
    const clearError = () => showError('');

    // Pick an icon based on file extension.
    const iconFor = (fileName) => {
        const ext = (fileName.split('.').pop() || '').toLowerCase();
        const svg = (body) => `<svg viewBox="0 0 32 32" width="28" height="28" role="presentation" aria-hidden="true">${body}</svg>`;

        if (ext === 'pdf') {
            return svg(`
                <rect x="6" y="4" width="18" height="24" rx="3" fill="#fef2f2" stroke="#b91c1c" stroke-width="1.25"/>
                <path d="M20 4v6h6" fill="#fee2e2" stroke="#b91c1c" stroke-width="1.25" stroke-linejoin="round"/>
                <path d="M12.5 13.5c1.8 4.6 3.7 6.7 5.6 6.2 1.9-.5.8-3.5-3.3-3.4-1.5 0-3.3.4-5.6 1.2" fill="none" stroke="#b91c1c" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M11.8 13.6c.4-.9.7-1.8.9-2.5" stroke="#b91c1c" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M12.6 18.8c-.5 1.1-.9 2-.9 2.7 0 1 .6 1.5 1.8 1.5.4 0 .7-.1 1-.2" stroke="#b91c1c" stroke-width="1.2" stroke-linecap="round"/>
            `);
        }
        if (ext === 'doc' || ext === 'docx') {
            return svg(`<rect x="5" y="3" width="18" height="26" rx="3" fill="#eef2ff" stroke="#4338ca" stroke-width="1.2"/><path d="M11 10h10" stroke="#4338ca" stroke-width="1.6" stroke-linecap="round"/><path d="M11 14h10" stroke="#4338ca" stroke-width="1.6" stroke-linecap="round"/><path d="M11 18h10" stroke="#4338ca" stroke-width="1.6" stroke-linecap="round"/><path d="M11 22h6" stroke="#4338ca" stroke-width="1.6" stroke-linecap="round"/>`);
        }
        if (ext === 'xls' || ext === 'xlsx') {
            return svg(`<rect x="5" y="3" width="18" height="26" rx="3" fill="#ecfdf3" stroke="#047857" stroke-width="1.2"/><path d="M11 10h10" stroke="#047857" stroke-width="1.6" stroke-linecap="round"/><path d="M11 14h10" stroke="#047857" stroke-width="1.6" stroke-linecap="round"/><path d="M11 18h10" stroke="#047857" stroke-width="1.6" stroke-linecap="round"/><path d="M11 22h6" stroke="#047857" stroke-width="1.6" stroke-linecap="round"/>`);
        }
        if (ext === 'zip' || ext === 'rar' || ext === '7z') {
            return svg(`<rect x="6" y="7" width="20" height="18" rx="3" fill="#fefce8" stroke="#a16207" stroke-width="1.2"/><rect x="10" y="5" width="8" height="4" rx="1" fill="#fefce8" stroke="#a16207" stroke-width="1.2"/><path d="M14 7h2" stroke="#a16207" stroke-width="1.4" stroke-linecap="round"/><path d="M16 11v6" stroke="#a16207" stroke-width="1.4" stroke-linecap="round"/><rect x="14" y="17" width="4" height="3" rx="0.8" fill="#fde68a" stroke="#a16207" stroke-width="1.1"/>`);
        }
        if (['png','jpg','jpeg','gif','webp','svg'].includes(ext)) {
            return svg(`<rect x="5" y="5" width="22" height="18" rx="3" fill="#eff6ff" stroke="#1d4ed8" stroke-width="1.2"/><path d="M10 17l4-4 4 5 4-3 3 4" stroke="#1d4ed8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="11" r="1.8" fill="#c7d2fe" stroke="#1d4ed8" stroke-width="1.2"/>`);
        }
        return svg(`<rect x="6" y="5" width="20" height="18" rx="3" fill="#f8fafc" stroke="#475569" stroke-width="1.2"/><path d="M11 11h10" stroke="#475569" stroke-width="1.6" stroke-linecap="round"/><path d="M11 15h10" stroke="#475569" stroke-width="1.6" stroke-linecap="round"/><path d="M11 19h6" stroke="#475569" stroke-width="1.6" stroke-linecap="round"/>`);
    };

    // Human-readable sizes for list view.
    const formatBytes = (bytes) => {
        if (!bytes) return '0 B';
        const k = 1024;
        const sizes = ['B','KB','MB','GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(i ? 1 : 0)) + ' ' + sizes[i];
    };

    // Render current selections into the list.
    const render = () => {
        list.innerHTML = '';
        Array.from(dt.files).forEach((file, idx) => {
            const item = document.createElement('div');
            item.className = 'umui-item';
            item.innerHTML = `
                <div class="umui-item-icon">${iconFor(file.name)}</div>
                <div class="umui-item-body">
                    <div class="umui-item-name" title="${file.name}">${file.name}</div>
                    <div class="umui-item-size">${formatBytes(file.size)}</div>
                    <div class="umui-actions">
                        <button type="button" class="umui-link blue" data-preview="${idx}">Preview</button>
                        <button type="button" class="umui-link red" data-remove="${idx}">Remove</button>
                    </div>
                </div>
            `;
            list.appendChild(item);
        });
    };

    // Basic client-side validations.
    const validateFile = (file) => {
        if (maxSizeKb && file.size > maxSizeKb * 1024) {
            return `File "${file.name}" exceeds ${maxSizeKb} KB.`;
        }
        if (acceptList.length) {
            const allowed = acceptList.some(ext => file.name.toLowerCase().endsWith(ext.toLowerCase()));
            if (!allowed) {
                return `File "${file.name}" is not an accepted type (${acceptList.join(', ')}).`;
            }
        }
        return null;
    };

    // Add dropped/selected files, enforce limits.
    const addFiles = (files) => {
        clearError();
        if (!files?.length) return;
        const incoming = allowMultiple ? Array.from(files) : [files[0]];
        if (!allowMultiple) {
            dt.items.clear();
        }
        let hadError = false;
        for (const file of incoming) {
            if (dt.files.length >= maxFiles) {
                showError(`Maximum ${maxFiles} ${maxLabel} allowed.`);
                hadError = true;
                break;
            }
            const error = validateFile(file);
            if (error) {
                showError(error);
                hadError = true;
                continue;
            }
            dt.items.add(file);
        }
        input.files = dt.files;
        if (!hadError) {
            clearError();
        }
        render();
    };

    // Remove file by index when user clicks "Remove".
    list.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove]');
        if (!btn) return;
        const idx = parseInt(btn.dataset.remove, 10);
        const items = Array.from(dt.items);
        items.splice(idx, 1);
        const next = new DataTransfer();
        items.forEach(item => next.items.add(item.getAsFile()));
        dt.items.clear();
        Array.from(next.files).forEach(f => dt.items.add(f));
        input.files = dt.files;
        render();
    });

    // Preview file in a new window when "Preview" is clicked.
    list.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-preview]');
        if (!btn) return;
        const idx = parseInt(btn.dataset.preview, 10);
        const file = dt.files[idx];
        if (!file) return;
        const url = URL.createObjectURL(file);
        window.open(url, '_blank');
    });

    // Input change handler for manual browse.
    input.addEventListener('change', (e) => addFiles(e.target.files));

    // Drag + drop support.
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('is-drag');
    });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('is-drag'));
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('is-drag');
        if (e.dataTransfer?.files?.length) {
            addFiles(e.dataTransfer.files);
        }
    });
})();
</script>
