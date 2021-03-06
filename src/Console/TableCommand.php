<?php

namespace Reishou\UniqueIdentity\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class TableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'uid:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the entity sequence table';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * Create a new queue job table command instance.
     *
     * @param Filesystem $files
     * @param Composer   $composer
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files    = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $table = $this->laravel['config']['uid.entity_table'];

        $this->replaceMigration(
            $this->createBaseMigration($table),
            $table,
            Str::studly($table)
        );

        $this->info('Migration created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the table.
     *
     * @param string $table
     * @return string
     */
    protected function createBaseMigration($table = 'jobs'): string
    {
        return $this->laravel['migration.creator']->create(
            'create_' . $table . '_table',
            $this->laravel->databasePath() . '/migrations'
        );
    }

    /**
     * Replace the generated migration with the job table stub.
     *
     * @param string $path
     * @param string $table
     * @param string $tableClassName
     * @return void
     * @throws FileNotFoundException
     */
    protected function replaceMigration(string $path, string $table, string $tableClassName)
    {
        $stub = str_replace(
            ['{{table}}', '{{tableClassName}}'],
            [$table, $tableClassName],
            $this->files->get(__DIR__ . '/stubs/entity.stub')
        );

        $this->files->put($path, $stub);
    }
}
