<?php
namespace Mrgrain\EloquentServiceInjection\Tests;

use Mrgrain\EloquentServiceInjection\ServiceInjectionTrait;

/**
 * Class OtherStub
 *
 * @property \Exception exception
 * @package Mrgrain\EloquentServiceInjection\Tests
 */
class OtherStub extends \stdClass
{
    use ServiceInjectionTrait;

    /**
     * list of injected services.
     *
     * @var array
     */
    protected $inject = [
        'exception' => \Exception::class,
        'stdClass'  => \stdClass::class
    ];
}
