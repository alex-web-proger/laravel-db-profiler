<?php


namespace Alexlen\Profiler\Profiler;


use Alexlen\Profiler\Logger\DbProfilerLogger;
use Alexlen\Profiler\Logger\FileProfilerLogger;
use Alexlen\Profiler\Logger\ProfilerLoggerInterface;
use Illuminate\Database\Events\QueryExecuted;

class Profiler
{
    protected ProfilerLoggerInterface $logger;


    public function __construct()
    {
        $this->logger = $this->driver();
    }


    public function addSql(QueryExecuted $query): void
    {
        if ($this->checkEnabledProfiler() && $this->checkDuration($query->time && $this->checkEnvironment())) {
            $backtraceData = false;
            if ($this->checkEnabledBacktrace()) {
                $backtraceData = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT, 30);
            }
            $backtrace = new Backtrace($backtraceData);
            $this->logger->save($query, $backtrace);
        }
    }

    protected function checkEnvironment(): bool
    {
        $environments = config('alexlen.profiler.environments', ['local']);
        $environments = is_array($environments) ? $environments : [];
        return in_array(app()->environment(), $environments);
    }

    protected function checkDuration($time): bool
    {
        return $time >= config('alexlen.profiler.duration_min', 1);
    }


    protected function checkEnabledBacktrace()
    {
        return config('alexlen.profiler.enabled_backtrace', true);
    }


    protected function checkEnabledProfiler()
    {
        return config('alexlen.profiler.enabled_profiler', true) && !app()->runningInConsole();
    }

    protected function driver():ProfilerLoggerInterface
    {
        $name = config('alexlen.profiler.driver', 'file');
        return $name=='db'? new DbProfilerLogger() : new FileProfilerLogger();
    }

}
