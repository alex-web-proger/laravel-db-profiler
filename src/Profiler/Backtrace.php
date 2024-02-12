<?php


namespace Alexlen\Profiler\Profiler;


class Backtrace
{
    public $fileName;
    public $filePath;
    public $line;


    public function __construct($backtraceData = false)
    {
        if ($backtraceData) {

            $location = collect($backtraceData)->filter(function ($trace) {
                return !isset($trace['file']) || (!str_contains($trace['file'], 'vendor') && !str_contains($trace['file'], 'ProfilerServiceProvider'));
            })->first();

            if ($location) {
                $this->filePath = str_replace(base_path() . '\\', '', $location['file']);
                $this->fileName = basename($this->filePath);
                $this->line = $location['line'];
            }
        }

    }
}
