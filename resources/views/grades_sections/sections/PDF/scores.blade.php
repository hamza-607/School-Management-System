<x-pdf
:title="'علامات الصف ' . ($section->grade->name) . ' - شعبة ' . ($section->name) . ' في مادة ال ' . ($subject->name) . (time())"
:paperTitle="'علامات الصف ' . ($section->grade->name) . ' - شعبة ' . ($section->name) . ' في مادة ال ' . ($subject->name)"
:headersTable="['اسم الطالب','العلامة']"
:dataOfTable="$scores"
:noDataMessage="'لا يوجد علامات للعرض'"
:countTitle="'عدد الطلاب'"
:countValue="count($scores)"
:filters="[
  [
  'label' => 'الفصل',
  'value' => $semester->name,
  ],
]"
:yearModel="$semester->academic_year"
></x-pdf>