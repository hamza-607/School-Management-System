@php
$staffType = $from;
    $headers = [
       'name' => 'الاسم',
       'phone' => 'رقم الهاتف',
       'email' => 'الإيميل',
       'date' => 'تاريخ التوظيف',
       'subject_id' => 'المادة',
       'staff_type' => 'نوع العمل',
       'salary' => 'الراتب',
       'is_active' => 'حالة الموظف',
       'user_is_active' => 'حالة الحساب',
    ];

    if ($staffType === 'teacher') {
        unset($headers['staff_type']);
    }

    if ($staffType === 'admin') {
        unset($headers['staff_type']);
        unset($headers['subject_id']);
    }

    if ($staffType === 'other') {
        unset($headers['subject_id']);
        unset($headers['user_is_active']);
    }
@endphp

<x-pdf :title="'قائمة الموظفين' . (time())" 
:paperTitle="'قائمة الموظفين'" 
:headersTable="$headers"
:dataOfTable="$staffMembers"
:filters="$filters" 
:countTitle="'عدد الموظفين'" 
:countValue="count($staffMembers)"
></x-pdf>