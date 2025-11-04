# 概要
LaravelでDDDやったらどんな感じに書けそうか試したくて開発してみました。

アーキテクチャは🧅を使用しています。

また、できるだけLaravelの便利な機能を使えたらフレームワークの良さも出るしいいなってことで

handlerには既存のLaravelのcontrollersを使い、

infra層では必要に応じてEloquentモデルも使用しています。(Domain層のモデルとは別にしている)

また、Requestクラスでバリデーション、レスポンスの整形にResourceクラスを使用しています。

APIの認証にはsanctumのトークン方式を採用しています。

テストにはpestフレームワークを採用しました。

今は１コントローラー１ユースケースみたいにしていますがファイル増えるだけでメリット無さそうなのと、

ドメインの生成メソッドにデフォルトのコンストラクタ使っているのが微妙な気がするのでそのあたりリファクタリングしようかなと思っています。

# 起動コマンド
```
sail up -d
```

# マイグレーション作成
```
sail artisan make:migration 名前
```

# マイグレーション実行
```
sail artisan migrate
```

# テスト実行
```
sail pest
```

# ログ表示
```
docker compose exec laravel.test tail -f storage/logs/laravel.log
```
