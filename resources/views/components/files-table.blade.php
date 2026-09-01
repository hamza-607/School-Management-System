 @props([
 'model' => null,
 'mainTitle' => null,
 'secTitle' => null,
 ])
 <div class="card shadow-sm border-0 header-card3 mt-4">
     <div class="card-body">

         <div class="d-flex align-items-center justify-content-between flex-wrap mx-2 my-3">
             <div class="tab-btn d-flex align-items-center gap-2">
                 <i class="bi bi-paperclip fs-3 text-primary"></i>
                 <h5 class="mb-0">{{ $mainTitle }}</h5>
             </div>
             <div class="search-box">
                 <label class="d-flex align-items-center">بحث:
                     <input id="sectionsSearchInput" type="search" class="form-control form-control-sm ms-2"
                         placeholder="بحث عن ملف..." value="{{ request('search') }}" title="اكتب اسم الملف او تاريخ رفعه">
                 </label>
             </div>
         </div>

         <div class="table-responsive">
             <table class="table align-middle">
                 <thead>
                     <tr>
                         <th>اسم الملف</th>
                         <th>رفع الملف من قبل</th>
                         <th>تاريخ الرفع</th>
                         <th>تحميل</th>
                     </tr>
                 </thead>
                 <tbody>
                     @forelse ($model->files as $file)
                     <tr>
                         <td class="fw-medium">
                             <i class="bx bx-file me-2 text-primary"></i>
                             {{ $file->name }}
                         </td>

                         <td><a href="{{ route('staff_members.show', [$file->uploudedBy->staff->id, 'from' => $file->uploudedBy->staff->staff_type]) }}">{{ $file->uploudedBy->name  }}</a></td>
                         <td>{{ $file->created_at->format('Y-m-d') }}</td>
                         <td>
                             <a href="{{ url(Storage::url($file->file_path)) }}" download="{{ $file->name }}"
                                 class="btn btn-sm btn-secondary">
                                 <i class="bi bi-download"></i>
                             </a>
                         </td>
                     </tr>
                     @empty
                     <tr>
                         <td colspan="5" class="text-center text-muted">{{ $secTitle }}</td>
                     </tr>
                     @endforelse
                 </tbody>
             </table>
         </div>
     </div>
 </div>
