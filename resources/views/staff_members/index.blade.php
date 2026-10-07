@extends('layouts/layoutMaster')

@section('title', 'الموظفين')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

    <script>
        $(document).ready(function () {

            // دالة موحدة لتحديث الرابط بناءً على جميع الفلاتر
            function updateFilters() {
                let search = $('#staff_membersearchInput').val();
                let perPage = $('#staffPerPage').val();
                let gender = $('#genderFilter').val();
                let status = $('#staffStatusFilter').val();
                let subject = $('#subjectFilter').val(); // المادة

                let url = new URL(window.location.href);

                // البحث
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }

                // عدد الصفوف
                if (perPage) {
                    url.searchParams.set('per_page', perPage);
                } else {
                    url.searchParams.delete('per_page');
                }

                // الجنس
                if (gender) {
                    url.searchParams.set('gender', gender);
                } else {
                    url.searchParams.delete('gender');
                }

                // الحالة
                if (status) {
                    url.searchParams.set('status', status);
                } else {
                    url.searchParams.delete('status');
                }

                // المادة (في حال وجود الفلتر)
                if (subject) {
                    url.searchParams.set('subject', subject);
                } else {
                    url.searchParams.delete('subject');
                }

                // إعادة الترقيم للصفحة الأولى
                url.searchParams.delete('page');

                window.location.href = url.toString();
            }

            // أحداث التغيير
            let timeout = null;
            $('#staff_membersearchInput').on('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    updateFilters();
                }, 500);
            });

            $('#staffPerPage').on('change', function () {
                updateFilters();
            });

            $('#genderFilter').on('change', function () {
                updateFilters();
            });

            $('#staffStatusFilter').on('change', function () {
                updateFilters();
            });

            $(document).on('change', '#subjectFilter', function () {
                updateFilters();
            });

            // حذف سجل
            $(document).on('click', '.delete-record', function (e) {
                e.preventDefault();
                let $btn = $(this);
                Swal.fire({
                    title: 'تأكيد الحذف',
                    html: `<div>
                                    هل أنت متأكد أنك تريد حذف هذا الموظف؟ 
                                    <br>
                                    <span class="text-danger">
                                    هذه العملية سوف تؤدي إلى حذف جميع الملفات المرتبطة بهذا الموظف
                                    </span>
                                    <br>
                                    <strong>هذه العملية لا يمكن التراجع عنها.</strong>
                                    </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger ms-2',
                        cancelButton: 'btn btn-secondary',
                        popup: 'swal2-popup-custom'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $btn.closest('form').submit();
                    }
                });
            });

            // النقر على الصف
            $(document).on('click', '.clickable-row', function (e) {
                if ($(e.target).closest('a').length || $(e.target).closest('button').length || $(e.target).closest('form').length) {
                    return;
                }
                window.location.href = $(this).data('href');
            });
        });
    </script>
@endsection

@section('content')
    <style>
        :root {
            --ink: #00352e;
            --ink-soft: #3a5350;
            --primary: #006559;
            --gold: #ab8347;
            --cream: #f5f8f7;
            --line: #dbe6e3;
            --sage: #006559;
            --sage-bg: #e6f1ef;
            --burgundy: #7a3540;
            --burgundy-bg: #f6ecec;
        }

        .swal2-container {
            z-index: 20000 !important;
        }

        .swal2-popup-custom {
            border-radius: 0.5rem;
        }

        .staff-filter-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
            padding: 1.35rem 1.5rem;
        }

        .staff-action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .3rem;
            border: 1px solid var(--line);
            background: #fff;
            transition: border-color .15s ease, background .15s ease;
        }

        .staff-action-btn:hover {
            border-color: var(--primary);
            background: var(--cream);
        }

        .staff-status-active {
            background: var(--sage-bg);
            color: var(--sage);
            border-color: #d3ddd5;
        }

        .staff-status-active .dot {
            background: var(--sage);
        }

        .staff-status-inactive {
            background: var(--burgundy-bg);
            color: var(--burgundy);
            border-color: #e6d4d4;
        }

        .staff-status-inactive .dot {
            background: var(--burgundy);
        }

        .staff-status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .85rem;
            border-radius: 1rem;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .staff-status-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .btn-staff-outline {
            border: 1px solid var(--line);
            background: var(--cream);
            color: var(--ink-soft);
            border-radius: .3rem;
            font-weight: 600;
            font-size: .9rem;
            padding: .55rem 1.1rem;
            transition: border-color .15s ease, color .15s ease;
        }

        .btn-staff-outline:hover {
            border-color: var(--primary);
            color: var(--ink);
        }
    </style>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">إدارة الموظفين</span> 
            <span class="border-bottom border-2 border-primary">القائمة</span>
        </h4>
    </div>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">قائمة الموظفين</h5>
            <div class="d-flex">
                <div class="btn-group me-2">
                    <button class="btn-staff-outline">
                        <i class="ti ti-download me-1"></i> طباعة
                    </button>
                </div>
                <a href="{{ route('staff_members.create', ['from' => $from]) }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> إضافة موظف جديد
                </a>
            </div>
        </div>

        <div class="staff-filter-bar">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">الجنس</label>
                    <select id="genderFilter" name="gender" class="selectpicker w-100" data-style="btn-default">
                        <option value="">الكل</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="fw-bold mb-1">حالة الموظف</label>
                    <select id="staffStatusFilter" name="status" class="selectpicker w-100" data-style="btn-default">
                        <option value="">الكل</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                @if ($from === 'teacher')
                    <div class="col-12 col-md-4">
                        <label class="fw-bold mb-1">المادة المسؤول عنها</label>
                        <select id="subjectFilter" name="subject" class="selectpicker w-100" data-style="btn-default"
                            data-width="100%">
                            <option value="">الكل</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <div class="row mx-2 my-3">
                <div class="col-md-6">
                    <div class="me-3">
                        <label class="d-flex align-items-center">عرض
                            <select id="staffPerPage" class="selectpicker ms-2 me-2" data-style="btn-default"
                                data-width="80px">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select> مدخلات
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                        <label class="d-flex align-items-center">بحث:
                            <input id="staff_membersearchInput" type="search" class="form-control form-control-sm ms-2"
                                placeholder="بحث عن موظف..." value="{{ request('search') }}"> </label>
                    </div>
                </div>
            </div>

            <table class="table table-hover border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>رقم الهاتف</th>
                        <th>الإيميل</th>
                        <th>تاريخ التوظيف</th>
                        @if ($from == 'other')
                            <th>نوع العمل</th>
                        @endif
                        @if ($from == 'teacher')
                            <th>المادة</th>
                        @endif
                        <th>حالة الموظف</th>
                        @if ($from !== 'other')
                        <th>حالة الحساب</th> @endif
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="studentTableBody">
                    @forelse ($staffMembers as $index => $staff)
                        <tr class="clickable-row" data-href="{{ route('staff_members.show', [$staff->id, 'from' => $from]) }}"
                            style="cursor:pointer;">
                            <td>{{ $index + 1 }}</td>
                            <td class="truncate">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $staff->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $staff->phone) }}" target="_blank"
                                    class="text-decoration-none me-3">
                                    {{ $staff->phone }}
                                </a>
                            </td>
                            <td>
                                <a href="https://mail.google.com/mail/?view=cm&to={{ $staff->email }}" target="_blank"
                                    class="text-decoration-none me-3">
                                    {{ $staff->email ?? '-' }}
                                </a>
                            </td>
                            <td class="truncate">{{ $staff->created_at->format('Y-m-d') }}</td>
                            @if ($from == 'other')
                                <td class="truncate">{{ $staff->staff_type }}</td>
                            @endif
                            @if ($from == 'teacher')
                                <td class="truncate"><a
                                        href="{{ route('subjects.show', $staff->subject->id) }}">{{ $staff->subject->name }}</a>
                                </td>
                            @endif
                            @php
                                $staffStatus = $staff->is_active ? 'نشط' : 'غير نشط';
                                $staffClass = $staff->is_active ? 'staff-status-active' : 'staff-status-inactive';
                            @endphp
                            <td>
                                <a href="{{ route('staffToggleStatus', $staff->id) }}"
                                    class="badge staff-status-pill {{ $staffClass }} px-3 py-2">
                                    <span class="dot"></span> {{ $staffStatus }}
                                </a>
                            </td>
                            @if ($from !== 'other')
                                @php
                                    $userStatus = ($staff->user ? $staff->user->is_active : 0) ? 'نشط' : 'غير نشط';
                                    $userClass = ($staff->user ? $staff->user->is_active : 0) ? 'staff-status-active' : 'staff-status-inactive';
                                @endphp
                                <td>
                                    <a href="{{ route('UserToggleStatus', $staff->id) }}"
                                        class="badge staff-status-pill {{ $userClass }} px-3 py-2">
                                        <span class="dot"></span> {{ $userStatus }}
                                    </a>
                                </td>
                            @endif
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    <a href="{{ route('staff_members.edit', [$staff->id, 'from' => $from]) }}"
                                        class="staff-action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                            width="18px" fill="#ab8347">
                                            <path
                                                d="M216-216h51l375-375-51-51-375 375v51Zm-72 72v-153l498-498q11-11 23.84-16 12.83-5 27-5 14.16 0 27.16 5t24 16l51 51q11 11 16 24t5 26.54q0 14.45-5.02 27.54T795-642L297-144H144Zm600-549-51-51 51 51Zm-127.95 76.95L591-642l51 51-25.95-25.05Z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('staff_members.destroy', [$staff->id, 'from' => $from]) }}"
                                        method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="staff-action-btn delete-record action-btn-hover">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                                width="18px" fill="#7a3540">
                                                <path
                                                    d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لا يوجد موظفين للعرض</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-end px-3 pb-3 mt-3">
                {{ $staffMembers->links() }}
            </div>
        </div>
    </div>

@endsection