<main class="flex-1 overflow-y-auto" style="height: calc(100vh - 80px); background-color: var(--bg-body);">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Content Wrapper -->
        <div class="p-0">
            
            <!-- Page Content -->
            <div id="book-content-wrapper" class="space-y-8">
                @for($i = 1; $i <= 5; $i++)
                <div class="rounded-lg shadow-lg p-8 relative page-container transition-transform duration-300 hover:shadow-xl" 
                     style="background-color: var(--bg-paper); box-shadow: var(--shadow-paper); font-family: var(--font-main);"
                     data-page="{{ $i }}">
                    
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <span class="text-xs text-gray-400" style="font-family: var(--font-ui);">آداب الفتوى - الإمام النووي</span>
                        <span class="text-sm font-bold text-gray-300" style="font-family: var(--font-ui);">{{ $i }}</span>
                    </div>

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none leading-loose" style="color: var(--text-main); line-height: 2;">
                        @if($i == 1)
                            <p class="text-center text-xl font-bold mb-2" style="color: var(--accent-color);">
                                بسم الله الرحمن الرحيم
                            </p>
                            <p class="mb-2">
                                الحمد لله رب العالمين، والصلاة والسلام على أشرف الأنبياء والمرسلين، نبينا محمد وعلى آله وصحبه أجمعين.
                            </p>
                            <p class="mb-2">
                                أما بعد: فإن من أعظم ما يحتاج إليه طالب العلم معرفة آداب الفتوى والمفتي والمستفتي، وذلك لأن الفتوى أمر عظيم، وشأن خطير، إذ هي توقيع عن الله تبارك وتعالى.
                            </p>
                        @elseif($i == 2)
                            <h3 class="text-xl font-bold mb-4" style="color: var(--accent-color);">شروط المفتي</h3>
                            <p class="mb-2">
                                ينبغي للمفتي أن يتحلى بآداب عديدة، منها ما يتعلق بعلمه وفقهه، ومنها ما يتعلق بأخلاقه وسلوكه، ومنها ما يتعلق بطريقة إفتائه وأسلوبه في التعامل مع المستفتين.
                            </p>
                            <p class="mb-2">
                                فمن الآداب المتعلقة بالعلم: أن يكون عالماً بأحكام الشريعة، عارفاً بأدلتها من الكتاب والسنة والإجماع والقياس، متمكناً من فهم النصوص وتطبيقها على الوقائع والنوازل.
                            </p>
                        @else
                            <p class="mb-2">
                                نص تجريبي للصفحة رقم {{ $i }} من الكتاب. يستمر النص هنا لملء الصفحة وتجربة التمرير العمودي المستمر.
                            </p>
                            <p class="mb-2">
                                يمكن للقارئ الاستمرار في التمرير للأسفل للانتقال من صفحة إلى أخرى بسلاسة دون الحاجة للنقر على أزرار التنقل.
                            </p>
                            <p class="mb-2">
                                هذا النمط من العرض يجعل كل صفحة تبدو كأنها ورقة منفصلة، مما يسهل التركيز ويعطي شعوراً مشابهاً للكتاب الورقي.
                            </p>
                            <p class="mb-2">
                                لوريم إيبسوم نص تجريبي، لتعبئة المكان. لوريم إيبسوم نص تجريبي، لتعبئة المكان.
                            </p>
                        @endif
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- Navigation Controls -->
            <x-book.reading-controls />
        </div>
    </div>
</main>