<?php

use App\Services\Generator\RandomKey;

/**
 * test of App\Services\Generator\RandomKey
 * 
 * @group Services
 */
class RandomKeyTest extends TestCase
{
    /** @var RondomKey */
    protected $target;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->target = new RandomKey();
    }

    /**
     * @test
     */
    public function it_can_be_obtained_array()
    {
        $actual = $this->target
                        ->setNecessaryNumber(4)
                        ->setRangeMin(1)
                        ->setRangeMax(204)
                        ->get()
                        ;

        $this->assertCount(4, array_unique($actual));
    }

    /**
     * @test
     */
    public function it_empty_array_can_be_obtained_by_default()
    {
        $this->assertSame([0], $this->target->get());
    }

    /**
     * @test
     */
    public function it_can_be_obtained_array_is_only_one()
    {
        $actual = $this->target
                        ->setNecessaryNumber(1)
                        ->setRangeMax(1)
                        ->get()
                        ;
        
        $actual2 = $this->target
                        ->init()
                        ->setNecessaryNumber(2)
                        ->setRangeMin(0)
                        ->setRangeMax(0)
                        ->get()
                        ;

        $actual3 = $this->target
                        ->init()
                        ->setNecessaryNumber(1)
                        ->setRangeMin(2)
                        ->setRangeMax(3)
                        ->get()
                        ;

        // if ($min > $max)
        $actual4 = $this->target
                        ->init()
                        ->setNecessaryNumber(1)
                        ->setRangeMin(2)
                        ->setRangeMax(1)
                        ->get()
                        ;

        $this->assertCount(1, $actual);
        $this->assertCount(1, $actual2);
        $this->assertCount(1, $actual3);
        $this->assertCount(1, $actual4);
    }
}
