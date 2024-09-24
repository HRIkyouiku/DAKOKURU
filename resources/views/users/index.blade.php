<x-app-layout>
    <h2>ユーザー一覧</h2>
    <div>
        <table>
            <thead>
                <tr>
                    <th>社員番号</th>
                    <th>名前</th>
                    <th>入社日</th>
                    <th>メールアドレス</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td><p>{{ $user->employee_no }}</p></td>
                    <td><p>{{ $user->name->fn_jp . $user->name->ln_jp}}</p></td>
                    <td><p>{{ $user->joining_date }}</p></td>
                    <td><p>{{ $user->email }}</p></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <div>
        <form action="{{ route('user.create') }}" method="get">
        @csrf
            <input type="submit" class="" value="ユーザーの新規登録">
        </form>
    </div>
</x-app-layout>