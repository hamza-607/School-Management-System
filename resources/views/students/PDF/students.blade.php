
<x-pdf 
:title="('قائمة الطلاب لسنة ' . ($year ? $year->name : '-')) . ' ' . (time())"
:paperTitle="('قائمة الطلاب لسنة ' . ($year ? $year->name : '-'))"
:yearModel="$year"
:headersTable="['الاسم', 'الجنس', 'الصف', 'الشعبة', 'رقم الهاتف', 'تاريخ الميلاد', 'حالة الطالب', 'تاريخ التسجيل', 'الحالة المادية']" 
:dataOfTable="$students" :noDataMessage="'لا يوجد طلاب للعرض'" 
:countTitle="'عدد الطلاب'"
:countValue="count($students)"
:filters="$filters"
></x-pdf>