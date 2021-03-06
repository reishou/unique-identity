<?php

namespace Reishou\UniqueIdentity\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Reishou\UniqueIdentity\Tests\TestCase;

class TableCommandTest extends TestCase
{
    public function testFileMigrationGenerated()
    {
        $table  = config('uid.entity_table');
        $dbpath = database_path('migrations/');
        foreach (File::glob($dbpath . '*' . 'create_' . $table . '_table.php') as $file) {
            unlink($file);
        }
        Artisan::call('uid:table');
        $migrations = File::glob($dbpath . '*' . 'create_' . $table . '_table.php');
        $this->assertCount(1, $migrations);
    }
}
