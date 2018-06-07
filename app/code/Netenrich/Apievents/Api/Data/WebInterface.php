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
interface WebInterface
{

    /**
     * Get web
     *
     * @return string
     */
    public function getWebsites();

      /**
     * Set web
     *
     * @param string $web
     * @return $this
     */
    public function setWebsites($web);
}
    