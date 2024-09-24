<x-app-layout>
    <h2>新規部署登録</h1>
    <div>
        <!-- 部署作成フォーム -->
        <div>
            <form method="post" action="{{route('department.store')}}">
                @csrf
                <div>
                    <label for="name_jp">作成する部署名</label>
                    <input id="name_jp" type="text" name="name_jp" placeholder="新しい部署名" value="{{ old('name_jp') }}">
                    <span>{{$errors->first('name_jp')}}</span>
                    <label for="name_en">部署名(英語)</label>
                    <input id="name_en" type="text" name="name_en" placeholder="新しい部署名(英語)" value="{{ old('name_en') }}">
                    <span>{{$errors->first('name_en')}}</span>
                    <input type="submit" value="登録">
                </div>
            </form>
        </div>
        <a href="{{ route('department.index') }}">部署一覧へ戻る</a>
    </div>
</x-app-layout>