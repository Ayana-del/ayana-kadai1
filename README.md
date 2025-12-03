
## 確認テスト_お問い合わせフォーム



FashionablyLate  
## 概要  
このプロジェクトは、Laravelを用いたWebsプリケーション開発の一環として実装されたお問い合わせフォーム機能です。  
ユーザーからのお問い合わせを受け付け、バリデーション、確認画面の表示、データベースへの保存までの一連のフローを構築します。  
(現段階の実装)

## 環境構築  
このプロジェクトはDocker Conpose を利用したコンテナ環境で動作します。  
  
### 前提条件  
*Docker / Docker Compose がインストールされていること。  
*Git がインストールされていること。  
  
### セットアップ手順  
  
リポジトリのクローン  
```bash
git clone git@github.com:git@github.com:Ayana-del/ayana-kadai1.git
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
composers install
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
  
  
###　アクセス
アプリケーションは以下のURLでアクセス可能です。  
| 画面 | URL |  
| **入力画面** | 'http://localhost/' |  
| **確認画面** | 'http://localhost/confirm/' |  
  
  
###　現在の実装機能  
現在のコミットで以下の機能が実装され、動作確認が完了しています。  
### 1.お問い合わせ入力画面('/')  
* **ルーティング**: 'GET/'は 'ContactController@index'にルーティングされます。  
* **データ取得**: 'Category' モデルからお問い合わせ種類（カテゴリ）を全て取得し、全て取得し、表示します。  
* **デザイン**: 'common.css', 'contact.css'のスタイルが適用されています。  
  
### 2.確認画面の遷移とバリデーション('POST /confirm')  
* **ルーティング**: 'POST /confirm' は、'ContactController@confirm'にルーティングされます。  
* **バリデーション**:'ContactRequest'を使用し、以下のルールーに基づいて入力データを厳格に検証します。  
    *必須項目チェック（’required’）  
    *形式チェック('email', 'regex', 'tel')  
    *文字数制限('max:8,max:120' など)  
    *外部キーチェック('exists:categories,id')  
* **エラー処理**: バリデーションエラーが発生した場合、入力した内容はそのまま自動で入力画面に戻り、エラーメッセージを表示  
* **データ整形**:電話番号のハイフン除去、せ別コード(1,2,3)から文字列への変換など、確認画面のデータを実行します。  
* **セッション管理**:検証済みの入力データをセッション('contact_data')に一時保存します。  
  
### 3. 確認画面の表示  
* **ビュー**:'confirm.blade.php'を使用し、成形されたデータ（カテゴリを含む）を表示します。  
* **アクションボタン**  
* **「修正」**: 'POST' /send'ルート経由で'back'フラグを送り、入力画面にセッションデータを保持したままリダイレクトします。（未実装）  
* **「送信」ボタン**: 'POST /send'ルートにデータを送信し、DB保存処理に進みます。  



## ER 図

![ER図](ER.drawio.png)
