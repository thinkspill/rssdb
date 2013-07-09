<?php

class ListingsTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	// DB::table('listings')->delete();
// title:string, body:text, price:integer, year:integer, date:date, url:string
        $listings = array(

            array(
                'title' => '2007 Ford Escape Hybrid with Navigation (san jose west) $9995',
                'body' => 'some random entry here',
                'price' => 9995,
                'year' => 2007,
                'date' => date('Y-m-d'),
                'url' => 'http://craigslist.com',
            ),

            array(
                'title' => '2011 Ford Escape Hybrid Limited Edition AWD (san jose) $25999',
                'body' => 'some random entry here',
                'price' => 25999,
                'year' => 2011,
                'date' => date('Y-m-d'),
                'url' => 'http://craigslist.com',
            ),

            array(
                'title' => '2010 Ford Escape Hybrid (berkeley north / hills) $19400',
                'body' => 'some random entry here',
                'price' => 19400,
                'year' => 2010,
                'date' => date('Y-m-d'),
                'url' => 'http://craigslist.com',
            ),

            array(
                'title' => '2011 Ford Escape Hybrid 15K miles (sacramento) $19700',
                'body' => 'some random entry here',
                'price' => 19700,
                'year' => 2011,
                'date' => date('Y-m-d'),
                'url' => 'http://craigslist.com',
            ),

            array(
                'title' => '2008 Ford Escape Hybrid 4x4  (berkeley) $15000',
                'body' => 'some random entry here',
                'price' => 15000,
                'year' => 2008,
                'date' => date('Y-m-d'),
                'url' => 'http://craigslist.com',
            ),

        );

        // Uncomment the below to run the seeder
         DB::table('listings')->insert($listings);
    }

}