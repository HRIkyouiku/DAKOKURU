<x-app-layout>
    <h2>新規社員登録</h1>
    <div>
        <form method="post" action="{{ route('user.store') }}">
        @csrf
            <div>
                <label for="fn_jp">名前(正式表示)</label>
                <input id="fn_jp" type="text" name="fn_jp" value="{{ old('fn_jp') }}">
                <span>{{ $errors->first('fn_jp') }}</span>

                <label for="fn_jp_hira">名前(ひらがな)</label>
                <input id="fn_jp_hira" type="text" name="fn_jp_hira" value="{{ old('fn_jp_hira') }}">
                <span>{{ $errors->first('fn_jp_hira') }}</span>

                <label for="fn_jp_kata">名前(カタカナ)</label>
                <input id="fn_jp_kata" type="text" name="fn_jp_kata" value="{{ old('fn_jp_kata') }}">
                <span>{{ $errors->first('fn_jp_kata') }}</span>

                <label for="fn_en">名前(英語)</label>
                <input id="fn_en" type="text" name="fn_en" value="{{ old('fn_en') }}">
                <span>{{ $errors->first('fn_en') }}</span>

                <label for="ln_jp">姓(正式表示)</label>
                <input id="ln_jp" type="text" name="ln_jp" value="{{ old('ln_jp') }}">
                <span>{{ $errors->first('ln_jp') }}</span>

                <label for="ln_jp_hira">姓(ひらがな)</label>
                <input id="ln_jp_hira" type="text" name="ln_jp_hira" value="{{ old('ln_jp_hira') }}">
                <span>{{ $errors->first('ln_jp_hira') }}</span>

                <label for="ln_jp_kata">姓(カタカナ)</label>
                <input id="ln_jp_kata" type="text" name="ln_jp_kata" value="{{ old('ln_jp_kata') }}">
                <span>{{ $errors->first('ln_jp_kata') }}</span>

                <label for="ln_en">姓(英語)</label>
                <input id="ln_en" type="text" name="ln_en" value="{{ old('ln_en') }}">
                <span>{{ $errors->first('ln_en') }}</span>

                <label for="oln_jp">旧姓(正式表示)</label>
                <input id="oln_jp" type="text" name="oln_jp" value="{{ old('oln_jp') }}">
                <span>{{ $errors->first('oln_jp') }}</span>

                <label for="oln_jp_hira">旧姓(ひらがな)</label>
                <input id="oln_jp_hira" type="text" name="oln_jp_hira" value="{{ old('oln_jp_hira') }}">
                <span>{{ $errors->first('oln_jp_hira') }}</span>

                <label for="oln_jp_kata">旧姓(カタカナ)</label>
                <input id="oln_jp_kata" type="text" name="oln_jp_kata" value="{{ old('oln_jp_kata') }}">
                <span>{{ $errors->first('oln_jp_kata') }}</span>

                <label for="oln_en">旧姓(英語)</label>
                <input id="oln_en" type="text" name="oln_en" value="{{ old('oln_en') }}">
                <span>{{ $errors->first('oln_en') }}</span>

                <label for="mn_jp">ミドルネーム(正式表示)</label>
                <input id="mn_jp" type="text" name="mn_jp" value="{{ old('mn_jp') }}">
                <span>{{ $errors->first('mn_jp') }}</span>

                <label for="mn_jp_hira">ミドルネーム(ひらがな)</label>
                <input id="mn_jp_hira" type="text" name="mn_jp_hira" value="{{ old('mn_jp_hira') }}">
                <span>{{ $errors->first('mn_jp_hira') }}</span>

                <label for="mn_jp_kata">ミドルネーム(カタカナ)</label>
                <input id="mn_jp_kata" type="text" name="mn_jp_kata" value="{{ old('mn_jp_kata') }}">
                <span>{{ $errors->first('mn_jp_kata') }}</span>

                <label for="mn_en">ミドルネーム(英語)</label>
                <input id="mn_en" type="text" name="mn_en" value="{{ old('mn_en') }}">
                <span>{{ $errors->first('mn_en') }}</span>

                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                <span>{{ $errors->first('email') }}</span>

                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" value="{{ old('password') }}">
                <span>{{ $errors->first('password') }}</span>

                <label for="employee_no">社員番号</label>
                <input id="employee_no" type="text" name="employee_no" value="{{ old('employee_no') }}">
                <span>{{ $errors->first('employee_no') }}</span>

                <label for="joining_date">入社日</label>
                <input id="joining_date" type="date" name="joining_date" value="{{ old('joining_date') }}">
                <span>{{ $errors->first('joining_date') }}</span>

                <div>
                    <label for="display_first_name">ファーストネームを先に表示</label>
                    <input id="display_first_name" type="checkbox" name="display_first_name" value="true" {{ old('display_first_name') == 'true' ? 'checked' : '' }}>
                    <span>{{ $errors->first('display_first_name') }}</span>
                </div>

                <input type="submit" value="登録">
            </div>
        </form>
        <a href="{{ route('user.index') }}">ユーザー一覧へ戻る</a>
    </div>
</x-app-layout>