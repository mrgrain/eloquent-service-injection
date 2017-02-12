<?php
namespace Mrgrain\EloquentServiceInjection\Tests;

use Illuminate\Support\Facades\App;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

/**
 * Class ServiceInjectionTraitTest
 *
 * @package Mrgrain\EloquentServiceInjection\Tests
 */
class ServiceInjectionTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down tests.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test if getting a service works for models.
     */
    public function testGetServiceForModel()
    {
        // Arrange
        App::shouldReceive('make')
            ->once()
            ->with(\Exception::class)
            ->andReturn(new \Exception);
        $model = new ModelStub();

        // Act
        $exception = $model->exception;

        // Assert
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    /**
     * Test if getting a service works for other object.
     */
    public function testGetServiceForOtherObject()
    {
        // Arrange
        App::shouldReceive('make')
            ->once()
            ->with(\Exception::class)
            ->andReturn(new \Exception);
        $object = new OtherStub();

        // Act
        $exception = $object->exception;

        // Assert
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    /**
     * Test if it correctly falls back to the parent if it is a model (or anything else with __get).
     */
    public function testGetServiceNotFoundForModel()
    {
        // Arrange
        App::shouldReceive('make')->never();
        $somethingElse = new \stdClass();

        // use a proxy partial to ensure parents setAttribute is called
        // This is a limitation of mockery not allowing to mock magic methods (__get, __set)
        $mock = \Mockery::mock(new ModelStub)->makePartial();
        $mock->shouldReceive('setAttribute')->once();
        $mock->shouldReceive('getAttribute')->once()->andReturn($somethingElse);

        // test against a raw model to validate fallback to model methods
        $model = new ModelStub;

        // Act
        $mock->noService  = $somethingElse;
        $model->noService = $somethingElse;

        // Assert
        $this->assertSame($somethingElse, $mock->noService);
        $this->assertSame($somethingElse, $model->getAttributeValue('noService'));
    }

    /**
     * Test if it fails correctly if no service is found on other objects.
     */
    public function testGetServiceNotFoundForOtherObject()
    {
        // Arrange
        App::shouldReceive('make')->never();
        $somethingElse = new \stdClass();

        $mock = \Mockery::mock('OtherStub')->makePartial();
        $mock->shouldReceive('setService', 'getService')->never();

        // test against a raw model to validate fallback to normal __get/__set functionality
        $model = new OtherStub;

        // Act
        $mock->noService  = $somethingElse;
        $model->noService = $somethingElse;

        // Assert
        $this->assertSame($somethingElse, $mock->noService);
        $this->assertSame($somethingElse, $model->noService);
    }

    /**
     * Test if setting a defined service to something else works for a model.
     */
    public function testSetServiceForModel()
    {
        // Arrange
        App::shouldReceive('make')->never();

        // Act
        $model            = new ModelStub();
        $somethingElse    = new \stdClass();
        $model->exception = $somethingElse;

        // Assert
        $this->assertSame($somethingElse, $model->exception);
    }

    /**
     * Test if setting a service to something else works for other objects.
     */
    public function testSetServiceForOtherObject()
    {
        // Arrange
        App::shouldReceive('make')->never();

        // Act
        $model            = new OtherStub();
        $somethingElse    = new \stdClass();
        $model->exception = $somethingElse;

        // Assert
        $this->assertSame($somethingElse, $model->exception);
    }

    /**
     * Test if it correctly falls back to the parent if it is a model (or anything else with __get).
     */
    public function testSetServiceNotFoundForModel()
    {
        // Arrange
        App::shouldReceive('make')->never();
        $somethingElse = new \stdClass();

        // use a proxy partial to ensure parents setAttribute is called
        // This is a limitation of mockery not allowing to mock magic methods (__get, __set)
        $mock = \Mockery::mock(new ModelStub)->makePartial();
        $mock->shouldReceive('setAttribute')->once();

        // Act
        $mock->noService = $somethingElse;
    }

    /**
     * Test if it correctly falls back to the parent if it is a model (or anything else with __get).
     */
    public function testSetServiceNotFoundForOtherObject()
    {
        // Arrange
        App::shouldReceive('make')->never();
        $somethingElse = new \stdClass();

        $mock = \Mockery::mock('OtherStub')->makePartial();
        $mock->shouldReceive('setService')->never();

        // Act
        $mock->noService = $somethingElse;
    }
}
