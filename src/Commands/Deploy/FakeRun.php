<?php

namespace Laravuewind\Commands\Deploy;

trait FakeRun
{
    protected static bool $fake = false;

    public static function fake(): void
    {
        static::$fake = true;
    }

}
