<?php
declare(strict_types=1);
/**
 * This file is part of the chronos project.
 *
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Node
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace Kairos\Config\Node;

use Kairos\Config\Configuration;

/**
 * String node
 *
 * This class is used to process a configuration string
 *
 * @category Node
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class StringNode extends Configuration
{
    /**
     * Construct
     *
     * The default StringNode constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setAllowedTypes(['string']);
    }
}
