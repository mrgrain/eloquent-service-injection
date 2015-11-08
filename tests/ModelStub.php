<?php
namespace Mrgrain\EloquentServiceInjection\Tests;

use Illuminate\Database\Eloquent\Model;
use Mrgrain\EloquentServiceInjection\ServiceInjectionTrait;

/**
 * Class ModelStub
 * @property \Exception exception
 * @package Mrgrain\EloquentServiceInjection\Tests
 */
class ModelStub extends Model
{
    use ServiceInjectionTrait;

    /**
     * list of injected services.
     * @var array
     */
    protected $inject = [
        'exception' => \Exception::class,
        'stdClass' => \stdClass::class
    ];
}
