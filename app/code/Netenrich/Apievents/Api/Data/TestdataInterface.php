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
interface TestdataInterface
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
	
	
	/**
     * Get customer items.
     *
     * @return Netenrich\Apievents\Api\Data\OrderInterface[]|null
     */
    public function getItems();

    /**
     * Set open items.
     *
     * @param Netenrich\Apievents\Api\Data\OrderInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);



}