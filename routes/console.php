<?php

use App\Console\Commands\MarcarRematriculaPendente;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Todo dia 1º de janeiro às 00:01 o sistema verifica crianças cujo
 * ano_letivo já passou e as marca como Pendente para rematrícula.
 */
/*Schedule::command(MarcarRematriculaPendente::class)
    ->yearlyOn(1, 1, '00:01')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/rematricula.log'));
*/
Schedule::command(MarcarRematriculaPendente::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/rematricula.log'));