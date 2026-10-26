<?php
namespace helpers;

use helpers\Response;

class InputValidator
{
    // Main validation engine. Compares raw data against defined rules.

    public static function validate(array $data, array $rules): array
    {
        $validated = [];

        foreach ($rules as $rule => $type) {
            
            // 1. Check if the rule exists and isn't empty/null
            if (!array_key_exists($rule, $data) || $data[$rule] === '' || $data[$rule] === null) {
                Response::jsonResponse(400, [
                    "status"  => "error",
                    "message" => "The rule '$rule' is required."
                ]);
            }

            // 2. Clean values if they are simple text strings
            $value = is_string($data[$rule]) ? trim($data[$rule]) : $data[$rule];

            // 3. Route to the correct type validation check
            switch ($type) {
                case "integer":
                    self::isInteger($rule, $value);
                    $validated[$rule] = (int) $value;
                    break;

                case "float": // Added specifically for E-commerce Product Prices!
                    self::isFloat($rule, $value);
                    $validated[$rule] = (float) $value;
                    break;

                case "string":
                    self::isString($rule, $value);
                    $validated[$rule] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); // XSS Protection!
                    break;
                
                case "email":
                    self::isValidEmail($rule, $value);
                    $validated[$rule] = strtolower($value); 
                    break;

                case "array": // Added for Cart batch actions
                    if (!is_array($value)) {
                        Response::jsonResponse(400, [
                            "status"  => "error",
                            "message" => "The rule '$rule' must be an array."
                        ]);
                    }
                    $validated[$rule] = $value;
                    break;

                default:
                    $validated[$rule] = $value;
            }
        }

        return $validated;
    }

    private static function isString(string $rule, $value): void 
    {
        if (!is_string($value) || trim($value) === '') {
            Response::jsonResponse(400, [
                "status"  => "error",
                "message" => "The rule '$rule' must be a valid text string."
            ]);
        }
    }

    private static function isInteger(string $rule, $value): void 
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            Response::jsonResponse(400, [
                "status"  => "error",
                "message" => "The rule '$rule' must be a whole number (integer)."
            ]);
        }
    }

    private static function isFloat(string $rule, $value): void 
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            Response::jsonResponse(400, [
                "status"  => "error",
                "message" => "The rule '$rule' must be a valid price/decimal number."
            ]);
        }
    }

    private static function isValidEmail(string $rule, $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            Response::jsonResponse(400, [
                "status"  => "error",
                "message" => "The rule '$rule' must be a properly formatted email address."
            ]);
        }
    }
}