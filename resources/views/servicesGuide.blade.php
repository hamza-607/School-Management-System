@extends('layouts/layoutMaster')

@section('title', 'دليل الخدمات')

@section('content')

<h1 class="text-center">صفحة تجريبية</h1>

    <div class="mb-4">
        <h4 class="fs-3 fw-bold text-body-emphasis d-inline-block pb-2 mb-0">
            <span class="d-block fs-6 fw-medium text-muted">مدرسة أُفق</span>
            <span class="border-bottom border-2 border-primary">دليل الخدمات</span>
        </h4>
        <p class="text-muted mt-2 mb-0">شرح مختصر لكل قسم في النظام وترتيب العمل خلال السنة الدراسية.</p>
    </div>

    <div class="row g-4">

        {{-- فهرس الصفحة --}}
        <div class="col-lg-3">
            <div class="card sticky-top" style="top: 6rem">
                <div class="card-header fw-bold">المحتويات</div>
                <div class="list-group list-group-flush">
                    <a href="#flow" class="list-group-item list-group-item-action">ترتيب العمل خلال السنة</a>
                    <a href="#year" class="list-group-item list-group-item-action">السنة الدراسية والفصول</a>
                    <a href="#grades" class="list-group-item list-group-item-action">الصفوف والشعب</a>
                    <a href="#people" class="list-group-item list-group-item-action">الطلاب والكادر</a>
                    <a href="#schedule" class="list-group-item list-group-item-action">البرامج الدراسية والجلسات</a>
                    <a href="#attendance" class="list-group-item list-group-item-action">الحضور والعقوبات</a>
                    <a href="#scores" class="list-group-item list-group-item-action">العلامات</a>
                    <a href="#promotion" class="list-group-item list-group-item-action fw-bold text-primary">الترفع والرسوب</a>
                    <a href="#files" class="list-group-item list-group-item-action">الملفات</a>
                    <a href="#faq" class="list-group-item list-group-item-action">رسائل وأسئلة شائعة</a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">

            {{-- ترتيب العمل --}}
            <div class="card mb-4" id="flow" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-route ti-md text-primary"></i>
                    <h5 class="mb-0">ترتيب العمل خلال السنة</h5>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">إضافة السنة الدراسية وفصولها ثم تفعيلها (السنة أولاً ثم الفصل).</li>
                        <li class="list-group-item">إضافة الصفوف بالترتيب من الأدنى للأعلى، ثم شعب السنة الجديدة.</li>
                        <li class="list-group-item">إضافة المواد والكادر وأولياء الأمور وتسجيل الطلاب في شعبهم.</li>
                        <li class="list-group-item">وضع البرامج الدراسية (جدول الحصص والمذاكرات والامتحانات).</li>
                        <li class="list-group-item">خلال الفصل: تفعيل الجلسات وأخذ الحضور وتسجيل العقوبات وإنهاء الجلسات.</li>
                        <li class="list-group-item">رصد علامات المذاكرات والامتحان النهائي لكل فصل.</li>
                        <li class="list-group-item">نهاية السنة: إضافة السنة التالية ثم تحديد الترفع والرسوب لكل شعبة.</li>
                        <li class="list-group-item">تفعيل السنة الجديدة وتوزيع الطلاب على شعبها.</li>
                    </ol>
                </div>
            </div>

            {{-- السنة والفصول --}}
            <div class="card mb-4" id="year" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-calendar-event ti-md text-primary"></i>
                    <h5 class="mb-0">السنة الدراسية والفصول</h5>
                </div>
                <div class="card-body">
                    <p>من قسم <strong>الإدارة &larr; السنوات الدراسية</strong>: أضف السنة بتاريخ بدايتها ونهايتها، ثم أضف فصولها، ثم اضغط «تفعيل» بجانب السنة وبجانب الفصل الحالي.</p>
                    <ul>
                        <li>لا يعمل أي قسم في النظام قبل وجود سنة فعالة وفصل فعال، وسيتم تحويلك لهذه الصفحة تلقائياً.</li>
                        <li>تفعيل سنة أو فصل ينعكس على جميع المستخدمين.</li>
                        <li>يمكن عرض بيانات سنة أخرى من قائمة السنة الدراسية في الشريط العلوي، دون تغيير السنة الفعالة في النظام.</li>
                        <li>كل فصل له امتحانه النهائي الخاص، وللمعلم إضافة أي عدد من المذاكرات في الفصل.</li>
                    </ul>
                </div>
            </div>

            {{-- الصفوف والشعب --}}
            <div class="card mb-4" id="grades" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-school ti-md text-primary"></i>
                    <h5 class="mb-0">الصفوف والشعب</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-3">
                        <li><strong>الصفوف</strong> ثابتة وغير مرتبطة بسنة. كل صف جديد يصبح «الصف التالي» للصف الذي أُضيف قبله، لذلك أضف الصفوف بالترتيب من الأدنى للأعلى.</li>
                        <li><strong>الشعب</strong> خاصة بكل سنة دراسية: تُنشأ مجموعة شعب جديدة كل سنة حسب عدد الطلاب.</li>
                        <li>الحضور والعلامات والبرامج الدراسية كلها مرتبطة بالشعبة، وبالتالي بالسنة.</li>
                    </ul>
                    <div class="alert alert-warning mb-0">لا تُعدّل ترتيب الصفوف بعد بدء استخدام النظام، لأن الترفع يعتمد على هذا الترتيب.</div>
                </div>
            </div>

            {{-- الأشخاص --}}
            <div class="card mb-4" id="people" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-users ti-md text-primary"></i>
                    <h5 class="mb-0">الطلاب والكادر</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">الطلاب وأولياء الأمور</h6>
                            <ul class="mb-0">
                                <li>الطالب سجل عام يبقى طوال سنواته، ويرتبط بكل سنة من خلال «تسجيله» في تلك السنة (الصف والشعبة).</li>
                                <li>أضف ولي الأمر ثم اربطه بالطالب.</li>
                                <li>إيقاف الطالب (غير فعال) يخفيه من قوائم الترفع والرسوب.</li>
                                <li>صفحة الطالب فيها علاماته وعقوباته وملفاته.</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">الكادر</h6>
                            <ul class="mb-0">
                                <li>المعلمون والإداريون والكادر العام لكل فئة قائمتها.</li>
                                <li>لكل موظف عقد وتعديلات راتب (مكافآت وخصومات).</li>
                                <li>الحساب يُنشأ للموظف برابط موقّع يصله، ويمكن إيقاف الحساب دون إيقاف الموظف.</li>
                                <li>المواد تُضاف من قسم المواد وتُسند للشعب من البرنامج الدراسي.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- البرامج والجلسات --}}
            <div class="card mb-4" id="schedule" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-clock-hour-4 ti-md text-primary"></i>
                    <h5 class="mb-0">البرامج الدراسية والجلسات</h5>
                </div>
                <div class="card-body">
                    <p>البرنامج الدراسي للشعبة هو قائمة الحصص: مادة + معلم + يوم ووقت. لكل حصة نوع:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>النوع</th><th>المعنى</th><th>بعد انتهائها</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>عادية</td><td>حصة متكررة في الجدول</td><td>تبقى في الجدول</td></tr>
                                <tr><td>مذاكرة</td><td>حصة يُرصد لها علامة (تُضاف من نافذة الحصص)</td><td>تعود حصة عادية</td></tr>
                                <tr><td>تعويضية</td><td>حصة لمرة واحدة</td><td>تُحذف من الجدول</td></tr>
                                <tr><td>امتحان نهائي</td><td>امتحان الفصل، تُحدَّد له علامته العظمى</td><td>تُحذف من الجدول</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mb-1 fw-bold">دورة الجلسة:</p>
                    <ol class="mb-0">
                        <li>الحصة مجدولة في البرنامج الدراسي.</li>
                        <li>عند موعدها يفعّلها المعلم من «الجلسات الفعالة» (تبدأ الجلسة ويُسجَّل وقت البدء).</li>
                        <li>داخل الجلسة يأخذ المعلم الحضور ويسجل العقوبات.</li>
                        <li>عند «إنهاء الجلسة» تُحفظ الجلسة في «سجل الجلسات» ويُسجَّل وقت الانتهاء.</li>
                        <li>إلغاء الحصة يُسجَّل أيضاً في السجل كجلسة ملغية.</li>
                    </ol>
                </div>
            </div>

            {{-- الحضور والعقوبات --}}
            <div class="card mb-4" id="attendance" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-checklist ti-md text-primary"></i>
                    <h5 class="mb-0">الحضور والعقوبات</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-3">
                        <li>الحضور يُؤخذ لكل طالب في كل جلسة من شاشة الجلسة الفعالة، ويُحفظ عند إنهاء الجلسة.</li>
                        <li>يظهر في الجلسة طلاب الشعبة المسجلون فيها فقط.</li>
                        <li>العقوبة تُسجَّل للطالب من نفس الشاشة بحالة «معلقة»، وتبقى ظاهرة له بعد نهاية السنة حتى تُعالج.</li>
                        <li>العقوبات المعلقة تظهر في جدول الترفع والرسوب لمساعدتك في القرار.</li>
                    </ul>
                    <div class="alert alert-info mb-0">حذف جلسة من «سجل الجلسات» إجراء حساس لأنه مرتبط بحضور وعلامات تلك الجلسة.</div>
                </div>
            </div>

            {{-- العلامات --}}
            <div class="card mb-4" id="scores" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-report ti-md text-primary"></i>
                    <h5 class="mb-0">العلامات</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-3">
                        <li>لكل فصل «مكونات علامة»: المذاكرات + الامتحان النهائي، ولكل مكون علامة عظمى.</li>
                        <li>يصبح المكوّن قابلاً للرصد بعد أن تُنهى جلسته.</li>
                        <li>المعلم يرصد علامات شعبه من <strong>رصد العلامات</strong>، ولا يمكن إدخال علامة أكبر من العظمى.</li>
                        <li>المدير يعدّل علامات طالب من صفحة الطالب.</li>
                        <li>الطالب الذي لم تُرصد له علامة لا تُحسب علامته في ذلك المكوّن.</li>
                    </ul>
                    <div class="alert alert-info mb-0">ارصد جميع علامات الفصلين قبل الترفع والرسوب، لأن القرار يعتمد على المجاميع.</div>
                </div>
            </div>

            {{-- الترفع والرسوب --}}
            <div class="card mb-4 border border-primary" id="promotion" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-arrow-big-up-lines ti-md text-primary"></i>
                    <h5 class="mb-0">الترفع والرسوب</h5>
                </div>
                <div class="card-body">

                    <p>هذه العملية تُنفَّذ مرة واحدة لكل شعبة في نهاية السنة من <strong>الإدارة &larr; قوائم الترفع والرسوب</strong>. النظام <strong>لا يقرر</strong> نجاح الطالب أو رسوبه تلقائياً؛ هو يعرض لك المؤشرات وأنت تحدد القرار لكل طالب.</p>

                    <h6 class="fw-bold mt-4">1) قبل أن تبدأ</h6>
                    <ul>
                        <li>أنهِ رصد كل علامات الفصلين (مذاكرات وامتحانات نهائية) لجميع المواد.</li>
                        <li><strong>أضف السنة الدراسية التالية</strong> (لا يلزم تفعيلها بعد). بدونها يرفض النظام فتح صفحة تحديد النتائج. السنة التالية هي أقرب سنة تبدأ بعد السنة الحالية.</li>
                        <li>راجع العقوبات المعلقة للطلاب.</li>
                    </ul>

                    <h6 class="fw-bold mt-4">2) خطوات التنفيذ</h6>
                    <ol class="list-group list-group-numbered mb-3">
                        <li class="list-group-item">افتح «قوائم الترفع والرسوب» ثم افتح السنة المطلوبة (تظهر السنوات كأقسام قابلة للفتح).</li>
                        <li class="list-group-item">اضغط «تحديد النتائج» على بطاقة الشعبة.</li>
                        <li class="list-group-item">راجع جدول كل طالب (شرح الأعمدة بالأسفل). يمكنك البحث بالاسم وتغيير عدد الصفوف.</li>
                        <li class="list-group-item">اختر لكل طالب «ناجح» أو «راسب». العدّاد في أعلى الصفحة يبيّن كم قراراً بقي.</li>
                        <li class="list-group-item">حدّد قراراً لكل طالب ثم اضغط «حفظ» وأكّد.</li>
                    </ol>

                    <h6 class="fw-bold mt-4">3) كيف تُحسب الأرقام في الجدول</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr><th style="width: 22%">العمود</th><th>طريقة الحساب</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">علامة كل فصل</td>
                                    <td>مجموع علامات الطالب في كل مكونات الفصل (كل المواد) ÷ مجموع العلامات العظمى لها × 100. المكوّن الذي لم تُرصد له علامة لا يدخل في العلامة ولا في العظمى.</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">المعدل السنوي</td>
                                    <td>متوسط النسب المئوية للفصول، أي أن لكل فصل الوزن نفسه.</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">المواد الراسبة</td>
                                    <td>المادة تُعدّ راسبة إذا كان مجموع علامات الطالب فيها (في كل الفصول) أقل من نصف مجموع علاماتها العظمى. تفاصيلها في نافذة منبثقة.</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">العقوبات</td>
                                    <td>كل عقوبات الطالب مع نوعها وتاريخها وحالتها، في نافذة منبثقة.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-secondary">
                        <p class="fw-bold mb-1">مثال</p>
                        <p class="mb-0">طالب علامته في الفصل الأول 170 من 200 (85%) وفي الفصل الثاني 120 من 200 (60%): معدله السنوي 72.5%. وفي الرياضيات حصل على 28 من 60 على مجموع الفصلين، أي أقل من النصف، فتظهر لديه مادة راسبة واحدة. هل ينجح؟ القرار لك بحسب سياسة المدرسة.</p>
                    </div>

                    <h6 class="fw-bold mt-4">4) ماذا يحدث عند الحفظ</h6>
                    <ul>
                        <li>يُسجَّل قرار كل طالب (ناجح/راسب) مع اسم من قرّر ووقت القرار.</li>
                        <li>يُنشأ للطالب تلقائياً تسجيل في السنة التالية <strong>بدون شعبة</strong>:
                            <ul>
                                <li>الناجح: يُسجَّل في الصف التالي.</li>
                                <li>الراسب: يُسجَّل في الصف نفسه.</li>
                            </ul>
                        </li>
                        <li>بعد الحفظ تُقفل الشعبة وتتحول بطاقتها إلى «عرض النتائج» فقط.</li>
                        <li>القرار نهائي ولا يمكن التراجع عنه من هذه الصفحة.</li>
                    </ul>

                    <h6 class="fw-bold mt-4">5) بعد الانتهاء من كل الشعب</h6>
                    <ol>
                        <li>أضف شعب السنة التالية.</li>
                        <li>فعّل السنة التالية وفصلها الأول من «السنوات الدراسية».</li>
                        <li>وزّع طلاب السنة الجديدة على شعبهم من قسم الطلاب.</li>
                    </ol>

                    <div class="alert alert-warning mb-0">
                        <ul class="mb-0">
                            <li>لا تفعّل السنة الجديدة قبل إنهاء النتائج لجميع الشعب.</li>
                            <li>الطالب غير الفعال لا يظهر في قائمة الشعبة، فراجع حالته قبل البدء.</li>
                            <li>الناجحون في أعلى صف ليس لهم صف تالٍ في النظام، فراجع الإدارة قبل الحفظ.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- الملفات --}}
            <div class="card mb-4" id="files" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-paperclip ti-md text-primary"></i>
                    <h5 class="mb-0">الملفات</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">يمكن إرفاق ملفات بالطالب وولي الأمر والموظف والمادة والشعبة من زر «إضافة ملف» في صفحة كل منهم.</p>
                    <ul class="mb-0">
                        <li>الأنواع المسموحة: صور وPDF وWord.</li>
                        <li>الحد الأقصى للملف الواحد 20 ميغابايت، ويمكن رفع أكثر من ملف معاً.</li>
                        <li>يظهر في الجدول اسم من رفع الملف وتاريخ الرفع، ويمكن البحث وتحميل الملف.</li>
                    </ul>
                </div>
            </div>

            {{-- أسئلة شائعة --}}
            <div class="card mb-4" id="faq" style="scroll-margin-top: 6rem">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-help-circle ti-md text-primary"></i>
                    <h5 class="mb-0">رسائل وأسئلة شائعة</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    تظهر رسالة «لا يوجد سنة دراسية فعالة» أو «لا يوجد فصل فعال»
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">فعّل سنة وفصلاً من «السنوات الدراسية». إذا كنت معلماً فتواصل مع الإدارة.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    تظهر رسالة «يجب إضافة السنة الدراسية التالية أولاً»
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">أضف السنة التالية من «السنوات الدراسية» بتاريخ بداية بعد بداية السنة الحالية، ثم ارجع لقوائم الترفع والرسوب.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    لا أرى طالباً في قائمة الترفع والرسوب
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">تأكد أنه مسجل في هذه الشعبة وأنه فعال.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    علامة طالب لا تظهر في المجموع
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">تحقق أن جلسة المذاكرة أو الامتحان قد أُنهيت وأن العلامة رُصدت للطالب.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    كيف أرى بيانات سنة سابقة؟
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">اختر السنة من قائمة السنة الدراسية في الشريط العلوي، فتُفلتر الصفحات حسب تلك السنة.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection