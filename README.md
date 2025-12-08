
## 確認テスト_お問い合わせフォーム



FashionablyLate  
## 概要  
このプロジェクトは、Laravelを用いたWebsプリケーション開発の一環として実装されたお問い合わせフォーム機能です。  
ユーザーからのお問い合わせを受け付け、バリデーション、確認画面の表示、データベースへの保存までの一連のフローを構築します。  
(現段階の実装)  
  
## 動作環境  
OS:macOS  
必須ツール:  
・Docker Desktop  
・Docker Compose

## 環境構築  
このプロジェクトはDocker Compose を利用したコンテナ環境で動作します。  
  
### 前提条件  
*Docker / Docker Compose がインストールされていること。  
*Git がインストールされていること。  
  
### セットアップ手順  
  
リポジトリのクローン  
```bash
git clone git clone git@github.com:Ayana-del/ayana-kadai1.git
```  
```bash  
cd ayana-kadai1
```  
コンテナの起動
```bash
docker-compose up -d --build
```  
PHPコンテナのログインと初期設定  
```bash
docker-compose exec php bash
```  
依存パッケージのインストール
```bash
composer install
```  
環境ファイルのコピー  
```bash
cp .env.example .env
```
アプリケーションキーの生成  
```bash
php artisan key:generate
```  
データベースのセットアップ  
マイグレーションと初期データ（カテゴリ）の投入  
```bash
php artisan migrate:fresh --seed
```  
  
  
### アクセス  
アプリケーションは以下のURLでアクセス可能です。  
| 画面 | URL |  
| **データベース管理** | 'http://localhost'  
| **入力画面** | 'http://localhost/' |  
| **確認画面** | 'http://localhost/confirm/' |  
  
  
###　現在の実装機能  
  
現在のコミットで以下の機能が実装され、動作確認が完了しています。  
### 1.お問い合わせ入力画面('/')  
* **ルーティング**: 'GET/'は 'ContactController@index'にルーティングされます。  
* **データ取得**: 'Category' モデルからお問い合わせ種類（カテゴリ）を全て取得し、全て取得し、表示します。  
* **デザイン**: 'common.css', 'contact.css'のスタイルが適用されています。  
  
### 2.確認画面の遷移とバリデーション('POST /confirm')  
* **ルーティング**: 'POST /confirm' は、'ContactController@confirmOrSend'にルーティングされます。  
* **バリデーション**:'ContactRequest'を使用し、以下のルールーに基づいて入力データを厳格に検証します。  
    *必須項目チェック（’required’）  
    *形式チェック('email', 'regex', 'tel')  
    *文字数制限('max:8,max:120' など)  
    *外部キーチェック('exists:categories,id')  
* **エラー処理**: バリデーションエラーが発生した場合、入力した内容はそのまま自動で入力画面にリダイレクトされ、エラーメッセージを表示  
* **データ整形**:電話番号のハイフン除去、性別コード(1,2,3)から文字列への変換など、確認画面のデータを実行します。  
* **セッション管理**:検証済みの入力データをセッション('contact_data')に一時保存します。  
  ### 3.完了画面の遷移(’GET /thanks')  
* **ルーティング**:処理成功後、Route：:get('/thanks)は、ContactController＠thanksにリダイレクトされます。  
* **目的**:POSTrクエスト直後の画面でリロードが実行されることによる二重送信を防ぐため、POST後の処理は常にGETルートへのリダイレクトで完了します。  
* **表示**:お問い合わせの受付が完了したことをユーザーに伝えるメッセージを表示します。  




READ
###　これからの実装と修正  
* **デザイン調整**:全画面のデザインは最後に調整  
* **ダミーデータの投入**:35件  
* **認証機能の確認**:会員登録画面・ログイン画面  
* **管理画面の作成**:検索(名前/メール・性別・お問い合わせの種類・日付カレンダー)・リセット・エクスポート・ページネーション・モーダル表示
## ER 図

![ER図](ER.drawio.png)
