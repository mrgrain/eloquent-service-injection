<?php
namespace Mrgrain\EloquentServiceInjection;

use Illuminate\Support\Facades\App;

/**
 * Class ServiceInjectionTrait
 *
 * @property array inject
 * @package Mrgrain\EloquentServiceInjection
 */
trait ServiceInjectionTrait
{
    /**
     * The injected services.
     *
     * @var array
     */
    protected $services = [];

    /**
     * Overwrite the dynamic attribute getter.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        try {
            return $this->getService($key);
        } catch (ServiceInjectionException $e) {
            if (is_callable('parent::__get')) {
                return parent::__get($key);
            }
        }
        return $this->{$key};
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        if ($this->isService($key)) {
            $this->setService($key, $value);
            return;
        }

        // parent is model
        if (is_callable('parent::__set')) {
            parent::__set($key, $value);
            return;
        }

        // other objects
        $this->{$key} = $value;
    }

    /**
     * Get a service, inject if needed.
     *
     * @param $key
     *
     * @return mixed
     * @throws ServiceInjectionException
     */
    protected function getService($key)
    {
        // error case
        if (!$this->isService($key)) {
            throw new ServiceInjectionException('Requested service not defined for injection.');
        }

        // return previously injected service
        if (!isset($this->services[$key])) {
            $this->setService($key, App::make($this->inject[$key]));
        }

        return $this->services[$key];
    }

    /**
     * Set a service property to the array
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    protected function setService($key, $value)
    {
        // set value
        $this->services[$key] = $value;
        return $this->services[$key];
    }

    /**
     * Is the given key a service
     *
     * @param $key
     *
     * @return bool
     */
    protected function isService($key)
    {
        return isset($this->inject) && isset($this->inject[$key]);
    }
}
