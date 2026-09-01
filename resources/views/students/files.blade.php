@extends('layouts.layoutMaster')

@section('title', 'إضافة ملفات للطلاب')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
@endsection

@section('page-script')
<script>
    let dt = new DataTransfer();
    const fileInput = document.getElementById('imageInput');
    const previewList = document.getElementById('previews-list');
    const uploadContent = document.getElementById('uploadContent');

    fileInput.addEventListener('change', function(e) {
        // نمر على الملفات الجديدة المحددة
        for (let i = 0; i < this.files.length; i++) {
            let file = this.files[i];

            // التأكد من عدم إضافة نفس الملف مرتين (اختياري)
            dt.items.add(file);

            let reader = new FileReader();
            reader.onload = function(event) {
                let previewHtml = `
                                            <div class="dz-preview dz-file-preview custom-preview-item" data-name="${file.name}">
                                                <div class="dz-details">
                                                    <div class="dz-thumbnail">
                                                        <img src="${file.type.startsWith('image/') ? event.target.result : 'https://cdn-icons-png.flaticon.com/512/2991/2991108.png'}" alt="${file.name}">
                                                    </div>
                                                    <div class="dz-filename"><span class="text-truncate">${file.name}</span></div>
                                                    <div class="dz-size"><strong>${(file.size / 1024).toFixed(1)}</strong> KB</div>
                                                </div>
                                                <a href="javascript:void(0)" class="dz-remove remove-file-btn" data-name="${file.name}">إزالة الملف</a>
                                            </div>`;
                previewList.insertAdjacentHTML('beforeend', previewHtml);
            };
            reader.readAsDataURL(file);
        }

        // تحديث الـ Input الفعلي ليحتوي على كل الملفات (القديمة + الجديدة)
        this.files = dt.files;
        checkEmpty();
    });

    // حذف ملف
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-file-btn')) {
            e.preventDefault();
            e.stopPropagation(); // منع فتح نافذة اختيار الملفات عند الحذف

            let fileName = e.target.getAttribute('data-name');
            e.target.closest('.custom-preview-item').remove();

            // إعادة إنشاء DataTransfer جديد بدون الملف المحذوف
            const newDt = new DataTransfer();
            for (let i = 0; i < dt.files.length; i++) {
                if (dt.files[i].name !== fileName) {
                    newDt.items.add(dt.files[i]);
                }
            }
            dt = newDt;
            fileInput.files = dt.files; // تحديث الانبوت
            checkEmpty();
        }
    });

    function checkEmpty() {
        if (dt.items.length > 0) {
            uploadContent.classList.add('d-none');
        } else {
            uploadContent.classList.remove('d-none');
        }
    }
</script>

<style>
    .custom-dz-wrapper {
        border: 2px dashed #d9dee3;
        border-radius: 0.5rem;
        min-height: 250px;
        background-color: #fcfcfd;
        position: relative;
        padding: 20px;
        transition: 0.3s;
    }

    .custom-dz-wrapper:hover {
        border-color: #0D9394;
    }

    /* الـ Input يجب أن يكون فوق كل شيء ليظل الصندوق قابلاً للضغط */
    #imageInput {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
        /* فوق رسالة الرفع */
    }

    .previews-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        position: relative;
        z-index: 20;
        /* فوق الانبوت ليتمكن المستخدم من الضغط على زر "إزالة" */
        pointer-events: none;
        /* يسمح للضغط بالمرور للأسفل (للـ Input) */
    }

    .custom-preview-item {
        pointer-events: auto;
        /* يعيد تفعيل الضغط فقط للبطاقة وزر الحذف */
        width: 160px;
        background: white;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        position: relative;
    }

    .dz-thumbnail {
        width: 100%;
        height: 120px;
        overflow: hidden;
        border-radius: 8px;
        margin-bottom: 8px;
        background: #f1f1f1;
    }

    .dz-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dz-filename {
        font-size: 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 80%;
    }

    .dz-remove {
        display: block;
        background: #ff3e1d;
        color: #fff !important;
        border-radius: 4px;
        padding: 4px;
        font-size: 11px;
        text-decoration: none;
        margin-top: 5px;
        position: relative;
        z-index: 30;
    }

    .light-style .dz-remove:hover {
        background: #ff3e1d;
    }
</style>
@endsection

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">الطلاب/</span> إضافة ملف للطالب {{ $theStudent->name }}
</h4>

<x-nav :student="$theStudent" />

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">ملفات الطلاب</h5>
    </div>
    <div class="card-body">

        <form action="{{ route('student.saveFile', $theStudent->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="custom-dz-wrapper">
                <input type="file" name="files[]" id="imageInput" multiple accept="image/*,.pdf,.doc,.docx">

                <div id="uploadContent" class="text-center mt-5">
                    <div class="mb-3"><i class="display-3 bx bx-cloud-upload text-primary"></i></div>
                    <h5>اسحب الملفات هنا أو اضغط للرفع</h5>
                    <small class="text-muted">يمكنك إضافة ملفات في كل مرة تضغط فيها هنا</small>
                </div>

                <div id="previews-list" class="previews-container"></div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">حفظ كافة الملفات</button>
            </div>
        </form>

    </div>
</div>
@endsection