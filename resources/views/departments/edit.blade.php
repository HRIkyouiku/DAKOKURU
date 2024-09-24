<x-app-layout>
    <h2>部署名変更</h1>
    <div>
        <!-- 部署変更フォーム -->
        <div>
            <p>現在の部署名</p>
            <p>{{$department->name_jp}}</p>

            <form method="post" action="{{ route('department.update', $department->id) }}">
                @csrf
                @method('PATCH')
                <div>
                    <label for="name_jp">変更後</label>
                    <input id="name_jp" type="text" name="name_jp" placeholder="新しい部署名" value="{{ old('name_jp') }}">
                    <span>{{$errors->first('name_jp')}}</span>
                    <label for="name_en">変更後(英語)</label>
                    <input id="name_en" type="text" name="name_en" placeholder="新しい部署名(英語)" value="{{ old('name_en') }}">
                    <span>{{$errors->first('name_en')}}</span>
                    <input type="submit" value="変更">
                </div>
            </form>
        </div>
        <a href="{{ route('department.index') }}">部署一覧へ戻る</a>
    </div>
</x-app-layout>