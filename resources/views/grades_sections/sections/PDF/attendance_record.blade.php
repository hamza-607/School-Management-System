<x-pdf
:title="'قوائم حضور الصف ' . ($theSection ? $theSection->grade->name : ' - ') . ' الشعبة ' . ($theSection ? $theSection->name : ' - ') . ' ' . (time())"
:paperTitle="'قوائم حضور الصف ' . ($theSection ? $theSection->grade->name : ' - ') . ' الشعبة ' . ($theSection ? $theSection->name : ' - ') . ' ' . (time())"
:noDataMessage="'لا يوجد سجلات للعرض'"
:yearModel="$year"
:headersTable="['التاريخ','الوقت','اسم الطالب','المادة','المعلم','الحالة']"
:dataOfTable="$attendance"
:countTitle="'عدد الحصص'"
:countValue="$finishedSessionsCount"
:filters="$filters"
:moreInfo="$avgAttendance"
></x-pdf>