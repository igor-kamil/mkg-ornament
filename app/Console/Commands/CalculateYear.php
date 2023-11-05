<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class CalculateYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-year';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the year of an item based on the dating attribute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::where('year_from', '!=', '')->get();
        foreach ($items as $item) {
            $year = Str::before($item->dating, ' – ');
            if (Str::contains($year, ' v. Chr.')) {
                $year = -1 * (int)Str::before($year, ' v. Chr.');
            }
            $item->update(['year' => $year]);
        }
    }
}
