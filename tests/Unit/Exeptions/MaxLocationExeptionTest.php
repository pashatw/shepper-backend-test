<?php

namespace Tests\Unit\Exeptions;

use PHPUnit\Framework\TestCase;
use App\Exceptions\MaxLocationException;
use Illuminate\Http\Request;


class MaxLocationExceptionTest extends TestCase
{
	protected $maxLocactionException;

    public function setUp(): void
    {
        parent::setUp();
        $this->maxLocactionException = new MaxLocationException();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    function testMaxLocation()
    {
        
    }
}
