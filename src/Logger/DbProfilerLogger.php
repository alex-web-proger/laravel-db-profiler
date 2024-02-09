<?php


namespace Alexlen\Profiler\Logger;


use Alexlen\Profiler\Profiler\Backtrace;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;

class DbProfilerLogger implements ProfilerLoggerInterface
{
    private bool $ignore = false;

    public function save(QueryExecuted $query, Backtrace $backtrace): void
    {
        if (!$this->ignore) {

            $this->ignore = true;

            DB::table('sql_profilers')->insert([
                    'created_at' => date('Y-m-d H:i:s'),
                    'env' => app()->environment(),
                    'duration' => $query->time,
                    'sql' => "'$query->sql'",
                    'file_name' => $backtrace->fileName,
                    'line' => $backtrace->line,
                    'file_path' => $backtrace->filePath
                ]
            );

        }else {
            $this->ignore = false;
        }
    }
}
