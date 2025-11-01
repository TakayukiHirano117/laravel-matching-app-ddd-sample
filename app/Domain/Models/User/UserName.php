<?php

namespace App\Domain\Models\User;

use InvalidArgumentException;

class UserName
{
  private string $value;

  // ユーザー名の最小・最大文字数制約
  private const MIN_LENGTH = 1;
  private const MAX_LENGTH = 50;

  /**
   * @param string $value ユーザー名
   * @throws InvalidArgumentException
   */
  public function __construct(string $value)
  {
    $trimmedValue = trim($value);

    if (empty($trimmedValue)) {
      throw new InvalidArgumentException("ユーザー名は空にできません。");
    }

    $length = mb_strlen($trimmedValue);
    if ($length < self::MIN_LENGTH) {
      throw new InvalidArgumentException(
        "ユーザー名は" . self::MIN_LENGTH . "文字以上である必要があります。"
      );
    }

    if ($length > self::MAX_LENGTH) {
      throw new InvalidArgumentException(
        "ユーザー名は" . self::MAX_LENGTH . "文字以下である必要があります。実際: {$length}文字"
      );
    }

    $this->value = $trimmedValue;
  }

  /**
   * 文字列からUserNameインスタンスを生成
   * 
   * @param string $value ユーザー名
   * @return self
   */
  public static function NewUserNameByVal(string $value): self
  {
    return new self($value);
  }

  /**
   * 値を文字列として取得
   * 
   * @return string
   */
  public function value(): string
  {
    return $this->value;
  }

  /**
   * 文字列表現
   * 
   * @return string
   */
  // public function __toString(): string
  // {
  //     return $this->value;
  // }

  /**
   * 等価性チェック
   * 
   * @param UserName $other
   * @return bool
   */
  public function equals(UserName $other): bool
  {
    return $this->value === $other->value;
  }
}