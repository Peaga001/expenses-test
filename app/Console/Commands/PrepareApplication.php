<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;


class PrepareApplication extends Command
{
    protected $signature   = 'prepare:application';
    protected $description = 'Faz as configurações necessárias da aplicação';

    public function handle(): void
    {
        $refresh = '';

        $progressBar = $this->output->createProgressBar(6);
        $this->info(PHP_EOL . 'Preparando o banco de dados da aplicação');

        sleep(3);

        $progressBar->advance(2);

        if(Schema::hasTable('expenses')){
            $refresh = ':refresh';
        }

        $progressBar->advance(2);

        Artisan::call("migrate$refresh --seed");

        sleep(3);

        $progressBar->finish();

        $this->info(PHP_EOL . 'Configurações finalizadas!');
    }
}
