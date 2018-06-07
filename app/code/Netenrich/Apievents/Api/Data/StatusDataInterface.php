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
interface StatusDataInterface
{

    /**
     * Get name
     *
     * @return string
     */
    public function getStatus();

      /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setStatus($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getResoponse();

      /**
     * Set name
     *
     * @param string $response
     * @return $this
     */
    public function setResponse($response);





}
