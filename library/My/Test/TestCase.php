<?php

class My_Test_TestCase extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        TestConfiguration::setupDatabase();
    }
}