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
