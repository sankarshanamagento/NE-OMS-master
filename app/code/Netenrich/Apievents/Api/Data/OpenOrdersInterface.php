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
interface OpenOrdersInterface
{

    /**
     * Get data
     *
     * @return string
     */
    public function getStatus();

      /**
     * Set data
     *
     * @param string $name
     * @return $this
     */
    public function setStatus($data);

    /**
     * Get response
     *
     * @return array
     */
    public function getResponse();

      /**
     * Set response
     *
     * @param array $response
     * @return $this
     */
    public function setResponse($response);





}
