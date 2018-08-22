<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

class scrapeNordstrom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:nordstrom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes a random page on Nordstrom';

    /**
     * @var array Collections :: Stored array of collections (ie. https://shop.nordstrom.com/c/{collection}) to scrape
     */
    protected $collections = [
        'womens-activewear-shop',
        'womens-coats',
        'womens-dresses-shop',
        'womens-jeans-shop'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $this->info("Collections to scrape: ");
        foreach ($this->collections as $collection) {
            $this->info("$collection");
        }

        $this->line("");
        $this->info('Starting scraping process...');

        foreach ($this->collections as $collection) {
            $this->scrape($collection);
        }

    }

    public function scrape($collection) {

        $this->info("Targeting: {$collection}");
        $this->line("-------------------------------------------------");

        sleep(3);

        $client = new Client();



            $this->info("Retrieving page... ");
            $crawler = $client->request('GET', env('NORDSTROM_URL') . "/{$collection}");

            $crawler->filter('div[class*="galleryItem"]')->each(function ($node) {

                $title = $node->filter('h3[class*="title"]');

                if ($title->count()) { // Node error,  skip this one

                    $title = $title->text();
                    $this->info("{$title}");
                }

            });


    }
}
