<?php

namespace App\Domain\Models\TalkRoom;

use InvalidArgumentException;

class LastMessage
{
  private string $value;

  private const MAX_LENGTH = 255;

  public function __construct(string $value)
  {
    if (strlen($value) > self::MAX_LENGTH) {
      throw new InvalidArgumentException("LastMessageは" . self::MAX_LENGTH . "文字以内である必要があります。");
    }

    $this->value = $value;
  }

  public static function NewLastMessageByVal(string $value): self
  {
    return new self($value);
  }

  public function value(): string
  {
    return $this->value;
  }

  public function equals(LastMessage $other): bool
  {
    return $this->value === $other->value;
  }
}