<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('新規勤務場所登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div>
            <form action="{{ route('work_places.store') }}" method="post">
            @csrf
                <div>
                    <p>作成する勤務場所名</p>
                    <input type="text" name="name" value="{{ old('name') }}">
                </div>
                <input type="submit" class="" value="作成">
            </form>
        </div>
    </div>

    <div>
        <a href="{{ route('work_places.index') }}">一覧に戻る</a>
    </div>
</x-app-layout>