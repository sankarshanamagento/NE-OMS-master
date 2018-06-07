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
interface TestwebdataInterface
{

    /**
     * Get name
     *
     * @return string
     */
    public function getStatus();

      /**
     * Set message
     *
     * @param string $name
     * @return $this
     */
    public function setStatus($message);

    

    /**
     * Get customer orders.
     *
     * @return Netenrich\Apievents\Api\Data\OrderInterface[]|null
     */
    public function getOrders();

    /**
     * Set open orders.
     *
     * @param Netenrich\Apievents\Api\Data\OrderInterface[] $orders
     * @return $this
     */
    public function setOrders(array $orders = null);



}