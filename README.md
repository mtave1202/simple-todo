# 課題: ToDoリストの作成

## 目的

PHPとMySQLを用いて、以下の要件を満たすToDoリストの作成を行う

## 概要

- やるべき必要のある作業(以下ToDo)をリストにして閲覧・登録・更新・削除などの管理を行いたい。
- ブラウザで操作できるようにWebページとしての作成する。

## 要件

- ブラウザからURLを入力することでToDoリストのあるWebページへアクセスできる
- ToDoリストへアクセスした時点で過去に登録されたToDoの一覧が表示される
- ToDoリストへ新しいToDoの登録ができる
- ToDoリストに登録されているToDoの内容が修正ができる
- ToDoリストに登録されているToDoの削除ができる
- ToDoリストに登録されているToDoを完了状態にすることができる
- ToDoのデータはMySQLを用いて管理する
- ページ遷移は行わず、シングルページアプリケーションとして作成する
- ページレイアウト・デザインは任意とする(レスポンシブ化も不問)

## 使用技術

- フロントエンド: HTML / CSS / JavaScript
- バックエンド: PHP 8.x
- データベース: MySQL 8.x
- サーバー環境: Apache (Ubuntu 22.04.3 LTSにて動作確認)

## 使用ライブラリ
 - composer: PHPのライブラリ管理
 - Valitron: PHPのvalidation用
 - phpdotenv: PHPの環境変数管理
 - jQuery: JavaScript効率化ライブラリ
 - FontAwesome: webアイコンフォント

## セットアップ手順

1. ファイル配置
 - 本リポジトリのファイルを任意のディレクトリに配置し、`public/` にWebサーバーディレクトリを設定してください。

2. **データベースの準備**
  - `app/sql/scheme.sql` をmysqlで実行し、データベースの作成を行ってください。

3. composerの実行とenvファイルの設定
 - 本ファイルを配置したディレクトリにて以下を実行してください
 ```bash
 composer install
 ```

 - composer実行後、`.env.tmp` をコピーし、`.env` を作成してください。
 ```bash
 cp .env.tmp .env
 vi .env
 ```

4. 3.で作成した.envファイルを編集し、データベースへの接続情報を設定してください。

5. 1.で設定したURLにブラウザでアクセスし、アプリが表示されることを確認してください。

## ファイル構成

treeコマンドにて出力

```
project/
├── app
│   ├── config.php               # 設定ファイル
│   ├── db.php                   # Database接続用クラス
│   ├── helpers.php              # 各種オリジナル関数
│   ├── _include.php             # apiからの各種読み出し用
│   ├── input.php                # 入力内容取得用クラス
│   └── sql
│       └── scheme.sql           # テーブル内容
├── composer.json
├── composer.lock
├── public
│   ├── api
│   │   ├── create.php          # todo追加
│   │   ├── delete.php          # todo削除
│   │   ├── edit.php            # todo編集
│   │   ├── list.php            # todo一覧取得
│   │   └── status.php          # todo状態変更
│   ├── css
│   │   └── index.css           # メインCSS
│   ├── images
│   │   └── close2.png
│   ├── index.php                # メイン画面
│   └── js
│       └── index.js             # メインJS
└── README.md                     # このファイル
```
