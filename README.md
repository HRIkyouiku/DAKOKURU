# 概要

勤怠管理システム『DAKOKURU（だこくる）』のリポジトリ

## 環境

- laravel（ver.9.52.16）
- LaravelSail
- LaravelBreeze
- MySQL 8.0
- phpMyAdmin
- Mailpit

## 環境構築に必要なツール

- docker
- Node.js
    - インストール方法（※ `node -v` を実行しバージョンが出力された場合は不要）
        - Mac https://qiita.com/kiharito/items/4785d4d54c967b8ddc9a<br>
        - Windows https://qiita.com/echolimitless/items/83f8658cf855de04b9ce

# 環境構築手順

1. ローカルにDAKOKURUリポジトリをloneする
    - `git clone https://github.com/tarou-yamada/DAKOKURU-fork.git`（ディレクトリ名は例）
2. cdコマンドで、cloneしたlaravelのルートディレクトに移動する
    - `cd ~/DAKOKURU-fork/`（ディレクトリ名は例）
3. .env.exampleをコピーし、.envファイルを作成する
    - `cp .env.example .env `
4. .envの内容を以下のように変更する
    - `vi .env`

修正前

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

修正後

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=dakokuru_db
DB_USERNAME=sail
DB_PASSWORD=password
```

5. 「vendor.zip」を解凍する
    - `unzip vendor.zip`
6. vendorディレクトリが作成できたことを確認する
    - `ls -la vendor` などで「vendor」ディレクトリ内に多くのディレクトリがあれば確認OK
7. laravelのルートディレクトリにてコンテナを立ち上げる
    - `./vendor/bin/sail up -d`
8. 以下の4つのコンテナが立ち上がったことを確認する
    - `./vendor/bin/sail ps`
        - kks-laravel.test-1
        - kks-mysql-1
        - kks-phpmyadmin-1
        - kks-mailpit-1
9. アプリケーションキーの作成
    - `./vendor/bin/sail artisan key:generate`
10. コンテナの中に入る
    - `./vendor/bin/sail bash` もしくは `./vendor/bin/sail shell`
11. パッケージをインストール
    - `npm install`
    - `npm run build`
12. コンテナから出る
    - `exit;`
13. マイグレーションを実行しテーブルを作成
    - `./vendor/bin/sail artisan migrate`
14. シーディングを実行しレコードを作成
    - `./vendor/bin/sail artisan db:seed --class=SimpleDataSetSeeder`
15. 「 http://localhost/login 」にアクセスし、Employee No→「99999」、Password→「password」でログインを確認する

