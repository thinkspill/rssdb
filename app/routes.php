<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get(
    '/',
    function () {
        ob_start();
        $regions = array(
            'sacramento',
            'sfbay',
            'losangeles',
            'reno',
            'goldcountry',
            'humboldt',
            'mendocino',
            'chico',
            'fresno',
            'hanford',
            'portland',
            'yubasutter',
            'visalia',
            'susanville',
            'stockton',
            'slo',
            'bakersfield',
            'lasvegas',
            'inlandempire',
            'orangecounty',
            'palmsprings',
            'sandiego',
            'santabarbara'
        );
        $searches = array(
            'ford escape hybrid',
            'honda cr-v',
            'toyota highlander',
            'subaru forester',
            'toyota rav4',
            'chevrolet equinox',
            'kia sportage',
            'ford explorer',
            'ford edge',
            'kia sorento',
            'hyundai veracruz',
            'hyundai tucson',
            'mazda cx-9',
            'toyota 4runner',
            'mercedes ML350',
            'subaru outback',
            'prius v',
            'ford flex',
            'mazda 5',

        );
        foreach ($regions as $region) {
            foreach ($searches as $search) {
                $search = urlencode($search);
                echo(get_rss_title($region, $search));
            }
        }

        return ob_get_clean();
    }
);

function get_rss_title($region, $search)
{
    $feed_url = 'http://' . $region . '.craigslist.org/search/cta?catAbb=cto&query=' . $search . '&sort=priceasc&srchType=T&format=rss';
    ob_start();
    $p = new SimplePie();
    $p->set_feed_url($feed_url);
    $p->set_cache_location('/tmp');
    $p->init();

    foreach ($p->get_items() as $item) {

        $listing = new Listing;
        $listing->title = $item->get_title();
        $listing->year = (int)get_year($item->get_title());
        $listing->price = (int)trim(get_price($item->get_title()), '$');
        $listing->url = $item->get_link();
        $listing->date = date('Y-m-d', strtotime($item->get_date()));
        $listing->region = $region;
        $listing->search = urldecode($search);
        if (is_null($listing->mileage = get_mileage($item->get_title())))
        {
            $listing->mileage = get_mileage($item->get_content());
        }
        $listing->body = $item->get_content();
        $listing->awd = get_awd($item->get_title() . " " . $item->get_content());

        var_dump($listing['attributes']);

        if ($listing->price == 0 || $listing->price < 5000) {
            continue;
        }

        try {
            $listing->save();
        } catch (Exception $e) {

        }

        echo '<hr>';

    }

    return ob_get_clean();
}


function get_year($string)
{
    preg_match('/20\d{2}/', $string, $matches);
    if (isset($matches[0])) {
        return ($matches[0]);
    } else {

        preg_match("/'[0-1][0-9]/", $string, $matches_abbr);
        if (isset($matches_abbr[0])) {
            return '20' . trim($matches_abbr[0], "'");
        } else {
            return 'No year found';
        }
    }
}

function get_price($string)
{
    preg_match('/\$\d{2,6}/', $string, $matches);
    if (isset($matches[0])) {
        return $matches[0];
    } else {
        return 'No price found.';
    }
}

function enforce_model_in_title($model, $title)
{
    $title = strtolower($title);
    if (strpos($title, $model) === false) {
        return false;
    } else {
        return true;
    }
}

function get_mileage($title)
{
    $title = str_ireplace('xxx', '000', $title); # people say 20,xxx miles, change that to 20,000
    $title = str_replace(',', '', $title); # change 20,000 to 20000
    $title = preg_replace('/\$\d{2,6}/', '', $title); # remove any prices
    $title = str_ireplace('k ', '000 ', $title); # remove any prices
    preg_match_all('/\d{5,6}/', $title, $matches);
    if (count($matches)) {
        foreach ($matches[0] as $match)
        {
            if ($match > 10000 && $match < 300000)
            {
                return $match;
            }
        }

    }
    return null;
}

function get_awd($string)
{
    if (stripos($string, 'awd') !== false)
    {
        return 1;
    }
    elseif (stripos($string, '4wd') !== false)
    {
        return 1;
    }
    elseif (stripos($string, '4x4') !== false)
    {
        return 1;
    }
    return 0;
}

Route::resource('listings', 'ListingsController');