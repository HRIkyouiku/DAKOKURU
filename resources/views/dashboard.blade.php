<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="typeing" data-speed="100">You're logged in!</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    const typeTarget = document.querySelectorAll('.typeing');

    let options = {
        rootMargin: '0px',
        threshold: .5
    };

    let callback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.intersectionRatio > .5 && !entry.target.classList.contains('active')) {
                let typeContent = entry.target.textContent;
                let typeSprit = typeContent.split('');
                let typeSpeed = entry.target.getAttribute('data-speed');
                entry.target.textContent = '';  // テキストを消す
                entry.target.classList.add('active');

                let typeLength = 0;
                let typeInterval = setInterval(() => {
                    if (typeLength < typeSprit.length) {
                        entry.target.textContent += typeSprit[typeLength];
                        typeLength++;
                    } else {
                        clearInterval(typeInterval);
                        // 全ての文字が表示された後、指定時間後に再度表示開始
                        setTimeout(() => {
                            entry.target.textContent = '';  // テキストをリセット
                            typeLength = 0;  // 文字位置をリセット
                            typeInterval = setInterval(() => {
                                if (typeLength < typeSprit.length) {
                                    entry.target.textContent += typeSprit[typeLength];
                                    typeLength++;
                                } else {
                                    clearInterval(typeInterval);  // 完了時に再度停止
                                }
                            }, typeSpeed);
                        }, 1000);  // 2秒後に再度表示
                    }
                }, typeSpeed);
            }
        });
    };

    let observer = new IntersectionObserver(callback, options);

    typeTarget.forEach(e => {
        observer.observe(e);
    });
</script>