<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('勤務場所編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <table>
            <thead>
                <tr>
                    <th scope="col">作業場所名</th>
                    <th scope="col">作成日</th>
                    <th scope="col">最終更新日</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $place->name }}</td>
                    <td>{{ $place->created_at }}</td>
                    <td>{{ $place->updated_at }}</td>
                </tr>
            </tbody>
        </table>

        <div>
            <form action="{{ route('work_places.update') }}" method="post">
            @csrf
            @method('PATCH')
                <div>
                    <p>変更後の勤務場所名</p>
                    <input type="text" name="name" value="{{ $place->name }}">
                </div>
                <input type="hidden" name="id" value="{{ $place->id }}">
                <input type="submit" class="" value="変更する">
            </form>
        </div>

        <div>
            <form action="{{ route('work_places.destroy') }}" method="post">
            @csrf
            @method('DELETE')
                <input type="hidden" name="id" value="{{ $place->id }}">
                <input type="submit" class="" value="削除する">
            </form>
        </div>
    </div>

    <div>
        <a href="{{ route('work_places.index') }}">一覧に戻る</a>
    </div>
</x-app-layout>