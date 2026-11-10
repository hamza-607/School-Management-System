<x-pdf
:title="'سجل حضور الطالب ' . ($theStudent->name) . (time())"
:paperTitle="'سجل حضور الطالب '.$theStudent->name"
:yearModel="$year"
:headersTable="['التاريخ','اليوم','المادة','المعلم','الحالة']"
:dataOfTable="$attendancesAsArray"
:noDataMessage="'لا يوجد سجلات للعرض'"
:countTitle="'عدد الحصص'"
:countValue="$countOfAttendances"
:filters="$filters"
:moreInfo="$moreInfo"
></x-pdf>