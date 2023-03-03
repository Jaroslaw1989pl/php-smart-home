<?php

declare(strict_types = 1);

namespace models;


class ValidationModel extends Database
{
    const RULE_REQUIRED   = "required";
    const RULE_MIN_LENGTH = "minLength";
    const RULE_MAX_LENGTH = "maxLength";
    const RULE_EQUAL      = "equal";
    const RULE_UNIQUENESS = "uniqueness";
    const RULE_PASSWORD   = "password";
    const RULE_EMAIL      = "email";

    private static mixed $input = null;


    public static function input(mixed $input): ValidationModel
    {
        self::$input = trim(htmlspecialchars($input));
        return new static();
    }

    public static function rules(string|array ...$rules)
    {
        try
        {
            foreach ($rules as $rule)
            {
                if (gettype($rule) === "string")
                    call_user_func("self::$rule");
                else if (gettype($rule) === "array")
                    call_user_func("self::$rule[0]", $rule[1]);
            }

            return self::$input;
        }
        catch (\Exception $error)
        {
            throw new \Exception($error->getMessage(), 422);
        }
    }

    /**
     * @throws \Exception
     */
    private static function required(): void
    {
        if (strlen(self::$input) === 0)
            throw new \Exception("Field required.");
    }

    /**
     * @throws \Exception
     */
    private static function minLength(int $value): void
    {
        if (strlen(self::$input) < $value)
            throw new \Exception("Minimum number of characters $value.");
    }

    /**
     * @throws \Exception
     */
    private static function maxLength(int $value): void
    {
        if (strlen(self::$input) > $value)
            throw new \Exception("$value characters exceeded.");
    }

    /**
     * @throws \Exception
     */
    private static function equal(string|int|float $value): void
    {
        if (self::$input !== $value)
            throw new \Exception("Inputs are not the same.");
    }

    /**
     * @throws \Exception
     */
    private static function email(): void
    {
        if (!filter_var(self::$input, FILTER_VALIDATE_EMAIL))
            throw new \Exception("Invalid email address");
    }

    /**
     * @throws \Exception
     */
    private static function password(): void
    {
        if (!preg_match("/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9_])/", self::$input))
            throw new \Exception("Password does not met requirements.");
    }


    // public function uniqueness(string $key = 'id' | 'name' | 'email', string $value): bool
    // {
    //     $result = $this->select("SELECT * FROM users WHERE $key = :value_0", [$value]);
    //     return count($result) > 0 ? false : true;
    // }
}