@props(['name', 'value' => '', 'label' => '', 'required' => false])

<div class="form-group">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif
    
    <div class="mt-1">
        <textarea 
            id="{{ $name }}" 
            name="{{ $name }}" 
            class="ckeditor shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
            @if($required) required @endif
        >{{ $value }}</textarea>
    </div>
</div>

@once
@push('styles')
<style>
    .ck-editor__editable {
        min-height: 300px;
    }
    .ck-content ul, .ck-content ol {
        padding-left: 20px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editors = document.querySelectorAll('.ckeditor');
    
    editors.forEach(function(editorElement) {
        ClassicEditor
            .create(editorElement, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo'
                ],
                image: {
                    toolbar: [
                        'imageTextAlternative', '|',
                        'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'
                    ],
                    styles: [
                        'full',
                        'alignLeft',
                        'alignRight'
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },
                language: 'vi',
                simpleUpload: {
                    // Endpoint ảnh sẽ được upload đến
                    uploadUrl: '{{ route("upload.image") }}',
                    
                    // Thông tin headers gửi kèm mỗi request
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            })
            .then(editor => {
                // Đăng ký sự kiện form submit để đảm bảo dữ liệu được lưu
                editor.model.document.on('change:data', () => {
                    editorElement.dispatchEvent(new Event('input'));
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
});
</script>
@endpush
@endonce 