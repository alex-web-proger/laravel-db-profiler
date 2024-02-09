<?php


namespace Alexlen\Profiler\Logger;


use Alexlen\Profiler\Profiler\Backtrace;
use Illuminate\Database\Events\QueryExecuted;

interface ProfilerLoggerInterface
{
    public function save(QueryExecuted $query, Backtrace $backtrace);
}
