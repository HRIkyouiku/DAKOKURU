<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('勤務場所一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div>
            <form action="{{ route('work_places.index') }}" method="get">
            @csrf
            <input type="text" name="keyword" class="" placeholder="勤務場所名で検索">
            <input type="submit" class="" value="検索">
        </form>
        </div>

        <div>
            <form action="{{ route('work_places.create') }}" method="get">
            @csrf
                <input type="submit" class="" value="新規作成">
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>作業場所名</th>
                    <th>作成日</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($places as $place)
                <tr>
                    <td>
                        <p>{{ $place->name }}</p>
                    </td>
                    <td>
                        <p>{{ $place->created_at }}</p>
                    </td>
                    <td>
                        <a href="{{ route('work_places.edit', $place->id) }}">編集</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>