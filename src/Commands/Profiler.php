<?php

namespace Alexlen\Profiler\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Profiler extends Command
{
    protected $signature = 'alexlen:profiler {--clear}';

    protected $description = 'Управление журналом sql-запросов';

    public function handle()
    {
        if ($this->option('clear')) {
            if($this->confirm('Вы уверены, что хотите очистить журнал, расположенный в базе данных?')) {
                DB::table('sql_profilers')->truncate();
                $this->info('Журнал, расположенный в базе данных, очищен');
            }else{
                $this->info("Операция отменена");
            }
            return;
        }
        
    }
}
