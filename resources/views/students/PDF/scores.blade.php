<x-pdf 
:title="'علامات للطالب ' . ($theStudent->name) . (time())"
:paperTitle="'علامات للطالب ' . $theStudent->name"
:yearModel="$year"
:dataOfTable="$scoresAsArray" 
:noDataMessage="'لا يوجد علامات للعرض'" 
:countTitle="'عدد المواد'"
:countValue="count($scoresAsArray)"
:multiHeader="1"
:mainHeader="$semesters"
:secundHeader="['الإختبارت','الامتحان النهائي','المجموع']"
:total="$total"
></x-pdf> 