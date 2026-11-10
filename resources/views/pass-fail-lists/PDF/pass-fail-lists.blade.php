@php
    // dd($semesters);
    $headers = ['اسم الطالب'];
    foreach ($semesters as $s) {
        $headers[] = $s->name;
    }
    $headers[] = 'معدل السنة';
    $headers[] = 'المواد الراسبة';
    $headers[] = 'العقوبات';
    $headers[] = 'النتيجة';
    // dd($headers);
@endphp


<x-pdf 
:title="('قائمة الطلاب الناجحين والراسبين في الصف ') . ($section ? $section->grade->name : ' - ') . (' الشعبة ') . ($section ? $section->name : '-')" 
:paperTitle="('قائمة الطلاب الناجحين والراسبين في الصف ') . ($section ? $section->grade->name : ' - ') . (' الشعبة ') . ($section ? $section->name : '-')" 
:yearModel="$year"
:headersTable="$headers"
:dataOfTable="$students"
:countTitle="'عدد الطلاب'"
:countValue="count($students)"
></x-pdf>