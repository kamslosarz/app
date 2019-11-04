<?php


namespace tests\FormViewTest;


use Collection\Collection;
use Form\FormView\HtmlElement;
use Mockery;
use PHPUnit\Framework\TestCase;

class HtmlElementTest extends TestCase
{
    public function testShouldConstructHtmlElement()
    {
        $resource = 'rosource-file-name.phtml';
        $attributes = Mockery::mock(Collection::class);
        $options = Mockery::mock(Collection::class);

        $htmlElement = new HtmlElement($resource, $attributes, $options);

        $this->assertInstanceOf(HtmlElement::class, $htmlElement);
    }

    public function testShouldGetResource()
    {
        $resource = 'rosource-file-name.phtml';
        $attributes = Mockery::mock(Collection::class);
        $options = Mockery::mock(Collection::class);

        $htmlElement = new HtmlElement($resource, $attributes, $options);

        $this->assertEquals($resource, $htmlElement->getResource());
    }

    public function testShouldGetAttribute()
    {
        $resource = 'rosource-file-name.phtml';
        $attributes = Mockery::mock(Collection::class)
            ->shouldReceive('get')
            ->andReturn('predefined-value')
            ->with('test', null)
            ->once()
            ->getMock()
            ->shouldReceive('get')
            ->with('default-test', 'default-value')
            ->once()
            ->andReturn('default-value')
            ->getMock();

        $options = Mockery::mock(Collection::class);

        $htmlElement = new HtmlElement($resource, $attributes, $options);

        $this->assertEquals('predefined-value', $htmlElement->getAttribute('test'));
        $this->assertEquals('default-value', $htmlElement->getAttribute('default-test', 'default-value'));

        $attributes->shouldHaveReceived('get')->with('test', null)->once();
        $attributes->shouldHaveReceived('get')->with('default-test', 'default-value')->once();
    }

    public function testShouldGetOption()
    {
        $resource = 'rosource-file-name.phtml';
        $options = Mockery::mock(Collection::class)
            ->shouldReceive('get')
            ->andReturn('predefined-value')
            ->with('test', null)
            ->once()
            ->getMock()
            ->shouldReceive('get')
            ->with('default-test', 'default-value')
            ->once()
            ->andReturn('default-value')
            ->getMock();
        $attributes = Mockery::mock(Collection::class);

        $htmlElement = new HtmlElement($resource, $attributes, $options);

        $this->assertEquals('predefined-value', $htmlElement->getOption('test'));
        $this->assertEquals('default-value', $htmlElement->getOption('default-test', 'default-value'));

        $options->shouldHaveReceived('get')->with('test', null)->once();
        $options->shouldHaveReceived('get')->with('default-test', 'default-value')->once();
    }

    public function testShouldCheckIfHasAttribute()
    {
        $resource = '';
        $options = Mockery::mock(Collection::class);
        $attributes = Mockery::mock(Collection::class)
            ->shouldReceive('offsetExists')
            ->with('test')
            ->andReturnTrue()
            ->getMock()
            ->shouldReceive('offsetExists')
            ->with('test-not-defined-option')
            ->andReturnFalse()
            ->getMock();

        $htmlElement = new HtmlElement($resource, $attributes, $options);

        $this->assertTrue($htmlElement->hasAttribute('test'));
        $this->assertFalse($htmlElement->hasAttribute('test-not-defined-option'));

        $attributes->shouldHaveReceived('offsetExists')->with('test')->once();
        $attributes->shouldHaveReceived('offsetExists')->with('test-not-defined-option')->once();
    }

    public function testShouldCheckIfHasOption()
    {
        $resource = '';
        $attributes = Mockery::mock(Collection::class);
        $options = Mockery::mock(Collection::class)
            ->shouldReceive('offsetExists')
            ->with('test')
            ->andReturnTrue()
            ->getMock()
            ->shouldReceive('offsetExists')
            ->with('test-not-defined-option')
            ->andReturnFalse()
            ->getMock();

        $htmlElement = new HtmlElement($resource, $attributes, $options);

        $this->assertTrue($htmlElement->hasOption('test'));
        $this->assertFalse($htmlElement->hasOption('test-not-defined-option'));

        $options->shouldHaveReceived('offsetExists')->with('test')->once();
        $options->shouldHaveReceived('offsetExists')->with('test-not-defined-option')->once();
    }
}