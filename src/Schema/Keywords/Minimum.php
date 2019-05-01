<?php
/**
 * @author Dmitry Lezhnev <lezhnev.work@gmail.com>
 * Date: 01 May 2019
 */
declare(strict_types=1);


namespace OpenAPIValidation\Schema\Keywords;


use OpenAPIValidation\Schema\Exception\ValidationKeywordFailed;
use Respect\Validation\Validator;

class Minimum
{
    /**
     * The value of "minimum" MUST be a number, representing a lower limit
     * for a numeric instance.
     *
     * If the instance is a number, then this keyword validates if
     * "exclusiveMinimum" is true and instance is greater than the provided
     * value, or else if the instance is greater than or exactly equal to
     * the provided value.
     *
     * The value of "exclusiveMinimum" MUST be a boolean, representing
     * whether the limit in "minimum" is exclusive or not.  An undefined
     * value is the same as false.
     *
     * If "exclusiveMinimum" is true, then a numeric instance SHOULD NOT be
     * equal to the value specified in "minimum".  If "exclusiveMinimum" is
     * false (or not specified), then a numeric instance MAY be equal to the
     * value of "minimum".
     *
     *
     * @param $data
     * @param number $minimum
     * @param bool $exclusiveMinimum
     */
    public function validate($data, $minimum, bool $exclusiveMinimum = false): void
    {
        try {
            Validator::numeric()->assert($data);
            Validator::numeric()->assert($minimum);

            if ($exclusiveMinimum && $data <= $minimum) {
                throw new \Exception(sprintf("Value %d must be greater or equal to %d", $data, $minimum));
            }

            if (!$exclusiveMinimum && $data < $minimum) {
                throw new \Exception(sprintf("Value %d must be greater than %d", $data, $minimum));
            }

        } catch (\Throwable $e) {
            throw ValidationKeywordFailed::fromKeyword("minimum", $data, $e->getMessage());
        }
    }
}