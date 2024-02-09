<?php


namespace Alexlen\Profiler\Logger;


use Alexlen\Profiler\Profiler\Backtrace;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;


class FileProfilerLogger implements ProfilerLoggerInterface
{

    public function save(QueryExecuted $query, Backtrace $backtrace): void
    {
        $message = $this->message($query->time, $query->sql, $backtrace->filePath, $backtrace->line);

        Log::build([
            'driver' => $this->chanel(),
            'path' => storage_path("logs/" . $this->logFilename()),
        ])->info($message);
    }

    protected function message($time, $sql, $file, $line): string
    {
        $time = $this->timeFormatter($time);
        $msg = "Time: $time ms   Sql: '$sql'";
        $msgFile = $file ? "  File: '" . $file . "'  Line: '" . $line . "'" : '';
        return $msg . $msgFile;
    }

    protected function timeFormatter($time): string
    {
        $time = round($time, 2);
        $int = (integer)$time;
        $floor = (integer)(($time - floor($time)) * 100);
        $floor = str_pad($floor, 2, '0', STR_PAD_RIGHT);
        return str_pad($int, 4, ' ', STR_PAD_LEFT) . '.' . $floor;
    }

    protected function logFilename()
    {
        return config('alexlen.profiler.log_filename', 'profiler/db-profiler.log');
    }

    protected function chanel():string
    {
        $chanel = config('alexlen.profiler.chanel', 'single');
        return $chanel <> 'single' ? 'daily' : 'single';
    }
}
