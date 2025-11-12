<?php

namespace App\Domain\Models\vo;

use InvalidArgumentException;

class Email
{
  private string $value;

  // メールアドレスの最大文字数制約
  private const MAX_LENGTH = 254; // RFC 5321に基づく

  /**
   * @param string $value メールアドレス
   * @throws InvalidArgumentException
   */
  public function __construct(string $value)
  {
    $trimmedValue = trim($value);

    // 空文字チェック
    if (empty($trimmedValue)) {
      throw new InvalidArgumentException("メールアドレスは空にできません。");
    }

    // 長さチェック
    if (strlen($trimmedValue) > self::MAX_LENGTH) {
      throw new InvalidArgumentException(
        "メールアドレスは" . self::MAX_LENGTH . "文字以下である必要があります。"
      );
    }

    // メールアドレス形式のチェック
    if (!filter_var($trimmedValue, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException("メールアドレスの形式が不正です: {$trimmedValue}");
    }

    $this->value = $trimmedValue;
  }

  public static function NewEmailByVal(string $value): self
  {
    return new self($value);
  }

  public function value(): string
  {
    return $this->value;
  }

  public function equals(Email $other): bool
  {
    return $this->value === $other->value;
  }
}
