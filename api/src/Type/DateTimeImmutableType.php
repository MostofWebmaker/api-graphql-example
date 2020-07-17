<?php

namespace App\Type;

use DateTime;
use Exception;
use GraphQL\Language\AST\Node;

/**
 * Class DateTimeImmutableType
 * @package App\Type
 */
class DateTimeImmutableType
{
    /**
     * @param \DateTimeImmutable $value
     * @return string
     */
    public static function serialize(\DateTimeImmutable $value)
    {
        return $value->format('Y-m-d H:i:s');
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     * @throws Exception
     */
    public static function parseValue($value)
    {
        return new \DateTimeImmutable($value);
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     * @throws Exception
     */
    public static function parseLiteral(Node $valueNode)
    {
        return new \DateTimeImmutable($valueNode->value);
    }
}
