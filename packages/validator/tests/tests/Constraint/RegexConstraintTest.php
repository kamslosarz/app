<?php

namespace tests\Constraint;

use PHPUnit\Framework\TestCase;
use Validator\Constraint\RegexConstraint;

class RegexConstraintTest extends TestCase
{
    public function testShouldValidateConstraintSuccess()
    {
        $regexConstraint = new RegexConstraint([
            'regex' => '/[a-zA-Z]+/',
            'matchAll' => false,
            'callback' => null
        ]);
        $regexConstraint->setValue('somevalue');
        $this->assertTrue($regexConstraint->isValid());
    }

    public function testShouldValidateConstraintSuccessWithMatchAll()
    {
        $regexConstraint = new RegexConstraint([
            'regex' => '/[a-zA-Z]+/',
            'matchAll' => true,
            'callback' => function ($matches) {
                return sizeof($matches[0]) == 2;
            }
        ]);

        $regexConstraint->setValue('somevalue somevalue');
        $this->assertTrue($regexConstraint->isValid());
    }

    public function testShouldValidateConstraintSuccessWithCallback()
    {
        $regexConstraint = new RegexConstraint([
            'regex' => '/[a-zA-Z]+/',
            'matchAll' => true,
            'callback' => /**
             * @param $matches
             * @return bool
             */ function ($matches) {
                return true;
            }
        ]);
        $regexConstraint->setValue('somevalue somevalue');
        $this->assertTrue($regexConstraint->isValid());
    }




    public function testShouldValidateConstraintFailed()
    {
        $regexConstraint = new RegexConstraint([
            'regex' => '/^[a-zA-Z]+$/',
            'matchAll' => false,
            'callback' => null
        ]);
        $regexConstraint->setValue('somevalue!asdas');
        $this->assertFalse($regexConstraint->isValid());
        $this->assertEquals('Regex \'/^[a-zA-Z]+$/\' does not match string \'somevalue!asdas\'', $regexConstraint->getError());
    }

    public function testShouldValidateConstraintFailedWithMatchAll()
    {
        $regexConstraint = new RegexConstraint([
            'regex' => '/[a-zA-Z]+/',
            'matchAll' => true,
            'callback' => function ($matches) {
                return sizeof($matches[0]) == 2;
            }
        ]);
        $regexConstraint->setValue('someva!!lue someval123!!ue');
        $this->assertFalse($regexConstraint->isValid());
        $this->assertEquals('Regex \'/[a-zA-Z]+/\' does not match string \'someva!!lue someval123!!ue\'', $regexConstraint->getError());
    }

    public function testShouldValidateConstraintFailedWithCallback()
    {
        $regexConstraint = new RegexConstraint([
            'regex' => '/[a-zA-Z]+/',
            'matchAll' => true,
            'callback' => /**
             * @param $matches
             * @return bool
             */ function ($matches) {
                return false;
            }
        ]);
        $regexConstraint->setValue('somevalue somevalue');
        $this->assertFalse($regexConstraint->isValid());
        $this->assertEquals('Regex \'/[a-zA-Z]+/\' does not match string \'somevalue somevalue\'', $regexConstraint->getError());
    }
}