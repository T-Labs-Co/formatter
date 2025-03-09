<?php
/*
 * This file is a part of package t-co-labs/formatter
 *
 * (c) T.Labs & Co.
 * Contact for Work: T. <hongty.huynh@gmail.com>
 *
 * We're PHP and Laravel whizzes, and we'd love to work with you! We can:
 *  - Design the perfect fit solution for your app.
 *  - Make your code cleaner and faster.
 *  - Refactoring and Optimize performance.
 *  - Ensure Laravel best practices are followed.
 *  - Provide expert Laravel support.
 *  - Review code and Quality Assurance.
 *  - Offer team and project leadership.
 *  - Delivery Manager
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TLabsCo\Formatter\Rules;

use TLabsCo\Formatter\Formatter;

interface FormatterRuleContract
{

    /**
     * @param Formatter $formatter
     * @return mixed
     */
    public function setFormatter(Formatter $formatter);

    /**
     * @param string $attribute
     * @param mixed $value
     * @return mixed
     */
    public function format($attribute, $value);
}
