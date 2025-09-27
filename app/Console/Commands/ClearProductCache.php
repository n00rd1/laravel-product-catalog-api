<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearProductCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear product filters cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        cache()->forget('product_filters');
        $this->info('Product filters cache cleared successfully!');
        
        return 0;
    }
}
