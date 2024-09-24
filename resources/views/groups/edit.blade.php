<h1>権限グループ情報変更</h1>
<form method="post" action="{{ route('group.update', $group->id) }}">
    @csrf
    @method('PATCH')
    <input type="submit" value="更新" onclick="return confirm('更新します。宜しいですか？')">
    <p>権限グループ名の変更</p>
    <label>変更前</label>
    <input type="text" name="nowname" value="{{ $group->name }}" readonly>
    <label>変更後</label>
    <input type="text" name="newname">
    @error('newname')
    <p>{{ $message }}</p>
    @enderror

    <p>権限の変更</p>
    <table>
        <tr>
        <th>権限グループ名</th>
        <th>権限</th>
        </tr>
        @foreach($group->group_authorities as $group_authority)
        <tr>
            <td>{{ $group_authority->target_group->name }}</td>
            @foreach($all_authority as $authority)
            @php
                // HACK:??以降は不要？ （該当する権限グループ名がない場合、変換前の文言を表示するようにしている）
                // HACK:view側に書くと使いまわしができないので、Controllerや共通の処理としてClassを作った方がいい
                $displayAuthority = $jp_authorities[$authority] ?? $authority;
            @endphp
            <td>
                <label for="authority_checkbox_{{ $group_authority->id }}_{{ $authority }}">
                <input type="checkbox" name="authority_check[{{ $group_authority->id }}][]" value="{{ $authority }}" id="authority_checkbox_{{ $group_authority->id }}_{{ $authority }}" @checked(in_array($authority, json_decode($group_authority->authorities)))>{{ $displayAuthority }}
                </label>
            </td>
            @endforeach
        </tr>
        @endforeach
    </table>

    <p>この権限グループに所属する社員の変更</p>
    <input type="text" class="word" placeholder="氏名または社員番号、部署名で検索">
    <button type="button" class="search">検索</button>
    <table>
        <tr>
            <th>社員番号</th>
            <th>氏名</th>
            <th>部署</th>
            <th>現在のグループ権限</th>
            <th>
                <button type="button" class="all">全部表示</button>
            </th>
        </tr>
        @foreach($users as $user)
        <tr class="users">
            <td>{{ $user->employee_no }}</td>
            <td>{{ $user->name->fn_jp . $user->name->ln_jp }}</td>
            @foreach($user->departments as $department)
            <td>{{ $department->name_jp }}</td>
            @endforeach
            @foreach($user->groups as $user_group)
            <td>{{ $user_group->name }}</td>
            @endforeach
            <td>
                @if($user->groups->contains('id', $group->id))
                <label for="affiliation_checkbox_{{ $loop->iteration }}" data-userId="{{ $user->id }}">
                <input type="checkbox" id="affiliation_checkbox_{{ $loop->iteration }}" checked>
                <span class="affiliation_chek">変更なし（所属中）</span>
                </label>
                @else
                <label for="yet_affiliation_checkbox_{{ $loop->iteration }}">
                <input type="checkbox" name="yet_affiliation_chek[]" value="{{ $user->id }}" id="yet_affiliation_checkbox_{{ $loop->iteration }}">
                <span class="yet_affiliation_chek">変更なし（未所属）</span>
                </label>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</form>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
  $(function() {
    $('input[type="checkbox"]').change(function() {
      const $label = $(this).closest('label');
      
      if ($(this).prop('checked')) {
        if ($(this).attr('id').startsWith('affiliation_checkbox')) {
          $label.find('span').text('変更なし（所属中）');
          $label.find('input[type="hidden"]').remove();
        } else if ($(this).attr('id').startsWith('yet_affiliation_checkbox')) {
          $label.find('span').text('追加');
        }
      } else {
        if ($(this).attr('id').startsWith('affiliation_checkbox')) {
          const userId = $label.data('userid');
          $label.find('span').text('解除');
          $('<input>')
          .attr({
            type: 'hidden',
            name: 'affiliation_check[]',
            value: userId
          })
          .appendTo($label);
        } else if ($(this).attr('id').startsWith('yet_affiliation_checkbox')) {
          $label.find('span').text('変更なし（未所属）');
        }
      }
    });

    $('.word').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        $('.search').click();
      }
    });

    $('.search').on('click', function() {
      const searchTerm = $('.word').val().toLowerCase();

      $('tr.users').each(function() {
        const employeeNo = $(this).find('td').eq(0).text().toLowerCase();
        const name = $(this).find('td').eq(1).text().toLowerCase();
        const department = $(this).find('td').eq(2).text().toLowerCase();

        if (employeeNo.includes(searchTerm) || name.includes(searchTerm) || department.includes(searchTerm)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    $('.all').on('click', function() {
      $('tr.users').show();
      $('.word').val('');
    });
  });
</script>