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
 * Integer node
 *
 * This class is used to process a configuration integer
 *
 * @category Node
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class IntegerNode extends Configuration
{
    /**
     * Construct
     *
     * The default IntegerNode constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setAllowedTypes(['int']);
    }
}
