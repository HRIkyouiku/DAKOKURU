<x-app-layout>
    <h2>部署詳細</h1>
    <div>
        <p>部署名：{{$department->name_jp}}</p>
        <a href="{{ route('department.edit', $department->id) }}">編集</a>
        <form action="{{ route('department.destroy', $department->id) }}" method="post">
            @csrf
            @method('DELETE')
            <input type="submit" value="削除">
        </form>

        <!-- 検索フォーム -->
        <div>
            <form method="get" action="{{ route('department.show', $department->id) }}">
                <div>
                    <label for="keyword">キーワード</label>
                    <input id="keyword" type="text" name="keyword" placeholder="検索したい名前を入力" value="{{ old('keyword') }}">
                    <input type="submit" value="検索">
                @if(request('keyword'))
                    <a href="{{ route('department.show', $department->id) }}" class="btn btn-secondary">クリア</a>
                @endif
                </div>
                <span>{{$errors->first('keyword')}}</span>
            </form>
        </div>

        <!-- 検索結果のヒット数表示 (検索が行われた場合のみ) -->
        @if($totalCount !== null)
            <p>検索結果: {{ $totalCount }} 件</p>
        @endif

        <!-- 所属ユーザー(名前)一覧 -->
        <div>
            <p>所属ユーザー</p>
            @forelse($userNames as $userName)
            {{$userName->fn_jp}}
            {{$userName->ln_jp}}
            @empty
            所属ユーザーが存在していません。
            @endforelse
        </div>

        <!-- ページネーション(検索結果保持) -->
        {{ $userNames->appends(request()->all())->links() }}

        <a href="{{ route('department.index') }}">部署一覧へ戻る</a>
    </div>
</x-app-layout>