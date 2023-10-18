<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductView;

class ViewsCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'views_сleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans old products views';

    /**
     * Create a new command instance.
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        ProductView::where('time', '<',  date('Y-m-d H:i:s', time() - 180))->delete();
    }
}
