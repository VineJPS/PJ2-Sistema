<?php

namespace App\Console\Commands;

use App\Models\Crianca;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarcarRematriculaPendente extends Command
{
    /**
     * Identificador do comando Artisan.
     *
     * Uso manual: php artisan criancas:marcar-pendente
     * Agendado:   executa todo dia 1º de janeiro às 00:01
     */
    protected $signature = 'criancas:marcar-pendente
                            {--dry-run : Exibe quais registros seriam afetados sem alterar o banco}';

    protected $description = 'Marca como Pendente toda criança Ativa cujo ano letivo já passou, sinalizando necessidade de rematrícula.';

    public function handle(): int
    {
        $anoAtual = (int) Carbon::now()->format('Y');

        // Busca crianças Ativas com ano_letivo preenchido e menor que o ano atual
        $query = Crianca::where('status', 'Ativo')
            ->whereNotNull('ano_letivo')
            ->where('ano_letivo', '<', $anoAtual);

        $total = $query->count();

        if ($total === 0) {
            $this->info('Nenhuma criança necessita de rematrícula.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn("[dry-run] {$total} criança(s) seriam marcadas como Pendente:");
            $query->each(function (Crianca $c) {
                $this->line("  • [{$c->id}] {$c->nome} — ano letivo {$c->ano_letivo}");
            });
            return self::SUCCESS;
        }

        // Atualização em lote — sem disparar eventos de model
        $atualizados = $query->update(['status' => 'Pendente']);

        $this->info("✔ {$atualizados} criança(s) marcada(s) como Pendente para rematrícula.");

        return self::SUCCESS;
    }
}
