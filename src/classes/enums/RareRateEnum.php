<?php


enum RareRateEnum: string
{
    case COMMON = "COMMON";
    case RARE = "RARE";
    case SPECIAL = "SPECIAL";

    public static function isValid(string $value): bool {
        return in_array($value, array_map(fn($case) => $case->value, self::cases()));
    }
}
