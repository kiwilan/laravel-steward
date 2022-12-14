<?php

namespace Kiwilan\Steward\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ReflectionClass;

class ScoutFreshCommand extends CommandSteward
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage models to search engine with Laravel Scout.';

    protected $models = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->title();

        $list = config('steward.scoutable.models');
        $this->info('Models to search engine: '.implode(', ', $list));
        $this->newLine();
        foreach ($list as $model) {
            $this->getScoutName($model);
        }

        try {
            $this->warn('Clean all models in search engine.');
            $this->newLine();
            foreach ($this->models as $key => $value) {
                Artisan::call('scout:flush "'.$key.'"', [], $this->getOutput());
                Artisan::call('scout:delete-index "'.$value.'"', [], $this->getOutput());
                $this->newLine();
            }

            $this->warn('Import all models in search engine.');
            $this->newLine();
            foreach ($this->models as $key => $value) {
                Artisan::call('scout:import "'.$key.'"', [], $this->getOutput());
                $this->newLine();
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }

        $this->info('Done.');

        return Command::SUCCESS;
    }

    public function getScoutName(string $model)
    {
        $instance = new $model();
        $class = new ReflectionClass($instance);
        $name = $class->getName();
        $name = str_replace('\\', '\\\\', $name);

        if (method_exists($instance, 'searchableAs')) {
            $this->models[$name] = $instance->searchableAs();
        }
    }
}
