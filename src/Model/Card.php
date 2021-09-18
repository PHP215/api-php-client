<?php

/**
 * @package Nekofar\Nobitex
 *
 * @author Milad Nekofar <milad@nekofar.com>
 */

declare(strict_types=1);

namespace Nekofar\Nobitex\Model;

/**
 * Class Card
 */
class Card
{
    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $bank;

    /**
     * @var string
     */
    public $owner;

    /**
     * @var boolean
     */
    public $confirmed;

    /**
     * @var string
     */
    public $status;
}
