<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Api\Data;

/**
 * @api
 */
interface OrderInterface
{

    /**
     * Get number
     *
     * @return string
     */
    public function getOrdernumber();

      /**
     * Set number
     *
     * @param string $number
     * @return $this
     */
    public function setOrdernumber($number);
}
    