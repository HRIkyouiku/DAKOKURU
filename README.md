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
    - Node.jsインストール方法↓
        - Mac https://qiita.com/kiharito/items/4785d4d54c967b8ddc9a<br>
        - Windows https://qiita.com/echolimitless/items/83f8658cf855de04b9ce
        - （※ `node -v` を実行しバージョンが出力された場合は不要）
- composer

# 環境構築手順

1. ローカルにDAKOKURUリポジトリをloneする
    - `git clone https://github.com/tarou-yamada/DAKOKURU-fork.git`（例）
2. cdコマンドで、cloneしたlaravelのルートディレクトに移動する
    - `cd ~/DAKOKURU-fork/` （例）
3. composerをinstall
    - `composer install`
4. .env.exampleをコピーし、.envファイルを作成する
    - `cp .env.example .env `
5. .envの内容を以下のように変更する
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

6. laravelのルートディレクトリにてコンテナを立ち上げる
    - `./vendor/bin/sail up -d`

7. 以下の4つのコンテナが立ち上がったことを確認する
    - `./vendor/bin/sail ps`
        - kks-laravel.test-1
        - kks-mysql-1
        - kks-phpmyadmin-1
        - kks-mailpit-1

8. アプリケーションキーの作成
    - `./vendor/bin/sail artisan key:generate`
9. LaravelBreezeの有効化
    - `composer require laravel/breeze --dev`
10. LaravelBreezeをインストールする
    - `./vendor/bin/sail artisan breeze:install`
    - インストール中に聞かれる質問にはそれぞれ以下のように入力しEnter
        - `Which stack would you like to install?` → blade
        - `Would you like to install dark mode support?` → no
        - `Would you prefer Pest tests instead of PHPUnit?` → no
11. パッケージをインストール
    - `npm install`
12. マイグレーションを実行しテーブルを作成
    - `./vendor/bin/sail artisan migrate`
13. シーディングを実行しレコードを作成
    - `./vendor/bin/sail artisan db:seed --class=SimpleDataSetSeeder`
14. 「 http://localhost/login 」にアクセスし、Employee No→「99999」、Password→「password」でログインを確認する

