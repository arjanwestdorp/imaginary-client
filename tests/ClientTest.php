<?php

namespace ArjanWestdorp\Imaginary\Test;

use ArjanWestdorp\Imaginary\Client;
use ArjanWestdorp\Imaginary\Exceptions\InvalidConfigException;
use ArjanWestdorp\Imaginary\Exceptions\UndefinedDefinitionException;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Setup the client config.
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = new Client([
            'url' => 'https://imaginary.com',
            'client' => 'imaginary',
        ]);
    }

    /** @test */
    function it_will_throw_an_exception_if_the_url_is_not_set_in_the_config()
    {
        $this->expectException(InvalidConfigException::class);

        $client = new Client(['client' => 'test']);
        $client->fetch('https://www.google.com/logo.jpg')->url();
    }

    /** @test */
    function it_will_throw_an_exception_if_the_client_is_not_set_in_the_config()
    {
        $this->expectException(InvalidConfigException::class);

        $client = new Client(['url' => 'https://imaginary.com']);
        $client->fetch('https://www.google.com/logo.jpg')->url();
    }

    /** @test */
    function it_can_generate_a_url_to_fetch_an_image()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')->url();

        $this->assertEquals('https://imaginary.com/imaginary/image/fetch/https://www.google.com/logo.jpg', $url);
    }

    /** @test */
    function it_can_generate_a_url_with_a_width_manipulation()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->width(100)
            ->url();

        $this->assertContains('w_100', $url);
    }

    /** @test */
    function it_can_generate_a_url_with_a_height_manipulation()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->height(100)
            ->url();

        $this->assertContains('h_100', $url);
    }

    /** @test */
    function it_can_generate_a_url_with_a_fit_manipulation()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->fit()
            ->url();

        $this->assertContains('c_fit', $url);
        $this->assertNotContains('g_', $url);
    }

    /** @test */
    function it_can_generate_a_url_with_a_fit_to_gravity_manipulation()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->fit('top')
            ->url();

        $this->assertContains('c_fit', $url);
        $this->assertContains('g_top', $url);
    }

    /** @test */
    function it_can_generate_a_url_with_a_radius_manipulation()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->circle(50)
            ->url();

        $this->assertContains('r_50', $url);
    }

    /** @test */
    function it_can_generate_a_url_to_fetch_an_image_with_multiple_manipulations()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->width(100)
            ->height(100)
            ->fit('top')
            ->circle(50)
            ->url();

        $this->assertEquals('https://imaginary.com/imaginary/image/fetch/w_100,h_100,c_fit,g_top,r_50/https://www.google.com/logo.jpg', $url);
    }

    /** @test */
    function it_can_define_a_manipulation_set_and_use_it()
    {
        $this->client->define('thumb', function (Client $client) {
            $client->width(100)
                ->fit('top');
        });

        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->thumb()
            ->url();

        $this->assertEquals('https://imaginary.com/imaginary/image/fetch/w_100,c_fit,g_top/https://www.google.com/logo.jpg', $url);
    }

    /** @test */
    function it_can_define_a_manipulation_set_and_use_it_with_multiple_parameters()
    {
        $this->client->define('thumb', function (Client $client, $size) {
            $client->width($size == 'big' ? 200 : 100)
                ->fit('top');

        });

        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->thumb('big')
            ->url();

        $this->assertEquals('https://imaginary.com/imaginary/image/fetch/w_200,c_fit,g_top/https://www.google.com/logo.jpg', $url);
    }

    /** @test */
    function it_will_throw_an_exception_if_an_undefined_definition_is_called()
    {
        $this->expectException(UndefinedDefinitionException::class);

        $this->client->fetch('https://www.google.com/logo.jpg')
            ->landscape();
    }

    /** @test */
    function it_will_show_the_url_string_when_echoing_the_object()
    {
        $url = $this->client->fetch('https://www.google.com/logo.jpg')
            ->width(100);

        $this->assertEquals('https://imaginary.com/imaginary/image/fetch/w_100/https://www.google.com/logo.jpg', $url);
        $this->assertEquals('https://imaginary.com/imaginary/image/fetch/w_100/https://www.google.com/logo.jpg', $url->url());
    }
}