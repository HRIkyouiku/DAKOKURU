<x-app-layout>
    <h2>部署一覧</h1>
    <div>
        <!-- 検索フォーム -->
        <div>
            <form method="get" action="{{route('department.index')}}">
                <div>
                    <label for="keyword">キーワード</label>
                    <input id="keyword" type="text" name="keyword" placeholder="検索したい部署名を検索" value="{{ old('keyword') }}">
                    <input type="submit" value="検索">
                @if(request('keyword'))
                    <a href="{{ route('department.index') }}" class="btn btn-secondary">クリア</a>
                @endif
                </div>
                <span>{{$errors->first('keyword')}}</span>
            </form>
        </div>

        <!-- 画面遷移ボタン -->
        <a href="{{ route('department.create') }}">部署新規追加</a>

        <!-- 部署登録が成功した場合表示 -->
        @if(session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif

        <!-- 検索結果のヒット数表示 (検索が行われた場合のみ) -->
        @if($totalCount !== null)
            <p>検索結果: {{ $totalCount }} 件</p>
        @endif

        <!-- 部署一覧 -->
        <div>
        @forelse($departments as $department)
            <div>
                {{$department->name_jp}}
                <a href="{{ route('department.show', $department->id) }}">詳細</a>
            </div>
        @empty
            <p>部署が存在しません</p>
        @endforelse

        <!-- ページネーション(検索結果保持) -->
        {{ $departments->appends(request()->all())->links() }}
        </div>

    </div>
</x-app-layout>