<?php

namespace App\Domain\Models\vo;

use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

class UuidVo
{
    private string $value;

    /**
     * @param string $value UUID
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException("Invalid UUID format: {$value}");
        }
        $this->value = $value;
    }

    /**
     * 既存のUUIDから生成
     * 
     * @param string $value UUID文字列
     * @return self
     */
    public static function NewUuidByVal(string $value): self
    {
        return new self($value);
    }

    /**
     * 新しいUUIDを生成
     * 
     * @return self
     */
    public static function create(): self
    {
        return new self(Uuid::uuid4()->toString());
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
     * @param UuidVo $other
     * @return bool
     */
    public function equals(UuidVo $other): bool
    {
        return $this->value === $other->value;
    }
}

