
<x-pdf
:title="' السجل الاكاديمي للطالب ' . ($theStudent->name) . (time())"
:paperTitle="' السجل الاكاديمي للطالب ' . $theStudent->name"
:headersTable="['السنة الدراسية','الصف','الشعبة','ناجح/راسب/غير محدد']"
:dataOfTable="$studentEnrollments"
:noDataMessage="'لا يوجد سنوات للعرض'"
></x-pdf>