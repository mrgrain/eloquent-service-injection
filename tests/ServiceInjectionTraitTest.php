<?php
namespace Mrgrain\EloquentServiceInjection\Tests;

use Illuminate\Support\Facades\App;
use PHPUnit_Framework_TestCase;

/**
 * Class ServiceInjectionTraitTest
 * @package Mrgrain\EloquentServiceInjection\Tests
 */
class ServiceInjectionTraitTest extends PHPUnit_Framework_TestCase
{
    public function testGetService()
    {
        // Arrange
        App::shouldReceive('make')
            ->once()
            ->with(\Exception::class)
            ->andReturn(new \Exception);
        $stub = new ModelStub();

        // Act
        $exception = $stub->exception;

        // Assert
        $this->assertInstanceOf(\Exception::class, $exception);
    }


    public function testGetServiceFail()
    {
        // Arrange
        $stub = \Mockery::mock(new ModelStub)
            ->shouldReceive('setAttribute')
            ->once()
            ->getMock();
        $stub->shouldReceive('getAttribute')->once();

        // Act
        $stub->some = null;
        $result = $stub->some;

        // Assert
        $this->assertEquals(null, $result);
    }

    public function testSetService()
    {
        // Arrange
        $stub = new ModelStub();
        $somethingElse = new \stdClass();

        // Act
        $stub->exception = $somethingElse;

        // Assert
        $this->assertSame($somethingElse, $stub->exception);
    }
}
