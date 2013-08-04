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
    '/stats',
    function () {

        $listings = DB::table('listings')
            ->select(DB::raw('floor(avg(price)) as price, count(*) as count, year'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return View::make('listings.stats', array('listings' => $listings));
    }
);

Route::get(
    '/models/{model?}',
    function ($model = null) {
        ob_start();

        if (is_null($model)) {

            $models = DB::table('listings')->distinct('search')->select('search')->get();

            foreach ($models as $m) {
                var_dump($m->search);
            }
        } else {
            $model = urldecode($model);
            $models = DB::table('listings')->where('search', $model)->get();

            foreach ($models as $m) {
                var_dump($m->search, $m->price, $m->mileage, $m->year);
            }

        }
        return ob_get_clean();
    }
);

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
            'santabarbara',
            'flagstaff',
            'mohave',
            'phoenix',
            'prescott',
            'showlow',
            'sierravista',
            'tucson',
            'yuma',
            'elko',
            'bend',
            'corvallis',
            'eastoregon',
            'eugene',
            'klamath',
            'medford',
            'oregoncoast',
            'roseburg',
            'salem',
            'saltlakecity',
            'atlanta',
            'austin',
            'boston',
            'chicago',
            'dallas',
            'denver',
            'detroit',
            'houston',
            'miami',
            'minneapolis',
            'newyork',
            'philadelphia',
            'raleigh',
            'washingtondc'
        );
        $searches = array(
            'ford escape hybrid',
            'ford escape -hybrid',
            '2012 honda cr-v', # >= 2012
            '2013 honda cr-v', # >= 2012
            '2006 toyota highlander hybrid', # >= 2011
            '2011 toyota highlander hybrid', # >= 2011
            '2012 toyota highlander hybrid', # >= 2011
            '2013 toyota highlander hybrid', # >= 2011
            '2012 toyota rav4', # >= 2012
            '2013 toyota rav4', # >= 2012
            'prius',
            'mazda cx-5',
            'mazda tribute hybrid',
            'mercury mariner hybrid',
            'nissan pathfinder hybrid',
            'hybrid suv',
            'buick encore',
            '2013 chevrolet equinox',
            '2013 gmc terrain',
            '2013 kia sportage',
            '2014 mitsubishi outlander',
            '2014 subaru forester',
            'subaru xv crosstrek',
            'nissan juke',
            'mitsubishi outlander',
            '2006 lexus rx 400h',
            '2007 lexus rx 400h',
            'hyundai tucson',


//            'subaru forester',
//            'chevrolet equinox',
//            'kia sportage',
//            'ford explorer',
//            'ford edge',
//            'kia sorento',
//            'hyundai veracruz',
//            'hyundai tucson',
//            'mazda cx-9',
//            'toyota 4runner',
//            'mercedes ML350',
//            'subaru outback',
//            'ford flex',

        );
        $c = 0;
        $start = microtime();
        foreach ($regions as $region) {
            foreach ($searches as $search) {
                $search = urlencode($search);
                $c += get_rss_title($region, $search);
            }
        }
        echo "Found $c items in ", (microtime() - $start);

        return ob_get_clean();
    }
);

Route::get(
    '/stats/1',
    function () {
        ob_start();
        $results = DB::select(
            "
            SELECT id, url,
                   price,
                   year,
                   date,
                   created_at,
                   region,
                   search,
                   mileage,
                   awd,
                   hybrid,
                   Date_format(Now(), '%Y') - year                              AS age,
                   ( ( Date_format(Now(), '%Y') - year ) * 15000 ) - mileage    AS
                   miles_over_under,
                   Round(mileage / ( Date_format(Now(), '%Y') - year ))         AS
                   miles_per_year,
                   price / Round(mileage / ( Date_format(Now(), '%Y') - year )) AS
                   price_per_mpy
            FROM   listings
            WHERE  1 = 1
               AND awd = 1
               AND hybrid = 1
            --   AND year >= 2008
            -- HAVING miles_over_under >= 0
            ORDER  BY miles_per_year ASC
            "
        );
     var_dump($results);
        return ob_get_clean();
    }
);
Route::get(
    '/stats/2',
    function () {
        ob_start();
        $results = DB::select(
            "
            SELECT id, url,
                   price,
                   year,
                   date,
                   created_at,
                   region,
                   search,
                   mileage,
                   awd,
                   hybrid,
                   Date_format(Now(), '%Y') - year                              AS age,
                   ( ( Date_format(Now(), '%Y') - year ) * 15000 ) - mileage    AS
                   miles_over_under,
                   Round(mileage / ( Date_format(Now(), '%Y') - year ))         AS
                   miles_per_year,
                   price / Round(mileage / ( Date_format(Now(), '%Y') - year )) AS
                   price_per_mpy
            FROM   listings
            WHERE  1 = 1
               AND awd = 1
               AND hybrid = 1
            --   AND year >= 2008
            -- HAVING miles_over_under >= 0
            ORDER  BY miles_per_year ASC
            "
        );
     var_dump($results);
        return ob_get_clean();
    }
);

function get_rss_title($region, $search)
{
    $feed_url = 'http://' . $region . '.craigslist.org/search/cta?catAbb=cto&query=' . $search . '&sort=priceasc&srchType=T&format=rss';

    $p = new SimplePie();
    $p->set_feed_url($feed_url);
    $p->set_cache_location('/tmp');
    $p->init();

    $c = 0;
    foreach ($p->get_items() as $item) {
        $c++;
        $listing = new Listing;
        $listing->title = $item->get_title();
        $listing->year = (int)get_year($item->get_title() . " " . $item->get_content());
        $listing->price = (int)trim(get_price($item->get_title()), '$');
        $listing->url = $item->get_link();
        $listing->date = date('Y-m-d', strtotime($item->get_date()));
        $listing->region = $region;
        $listing->search = urldecode($search);
        if (is_null($listing->mileage = get_mileage($item->get_title()))) {
            $listing->mileage = get_mileage($item->get_content());
        }
        $listing->body = $item->get_content();
        $listing->awd = get_awd($item->get_title() . " " . $item->get_content());
        $listing->hybrid = get_hybrid($item->get_title() . " " . $item->get_content());


        if ($listing->price == 0 || $listing->price < 5000) {
            continue;
        }

//        var_dump($listing['attributes']);

//        echo '<hr>';

        try {
            $listing->save();
        } catch (Exception $e) {

        }

    }

    return $c;
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
        foreach ($matches[0] as $match) {
            if ($match > 10000 && $match < 300000) {
                return $match;
            }
        }

    }
    return null;
}

function get_awd($string)
{
    if (stripos($string, 'awd') !== false) {
        return 1;
    } elseif (stripos($string, '4wd') !== false) {
        return 1;
    } elseif (stripos($string, '4x4') !== false) {
        return 1;
    }
    return 0;
}

function get_hybrid($string)
{
    if (stripos($string, 'hybrid') !== false) {
        return 1;
    }
    return 0;
}

Route::resource('listings', 'ListingsController');

function get_tmv($style_id)
{
    $key = "";
    $url = "http://api.edmunds.com/v1/api/tmv/tmvservice/calculatetypicallyequippedusedtmv?api_key=$key&styleid=$style_id&zip=95959&fmt=json";
    $res = json_decode(file_get_contents($url));
    var_dump($res);

}

function get_make_id($make, $model, $year)
{
    $key = "";
    $url = "http://api.edmunds.com/v1/api/vehicle/$make/$model/$year?api_key=$key&fmt=json";
    $res = json_decode(file_get_contents($url));
    return $res->modelYearHolder[0]->makeId;
}

function get_model_year_id($make, $model, $year)
{
    $key = "";
    $url = "http://api.edmunds.com/v1/api/vehicle/$make/$model/$year?api_key=$key&fmt=json";
    $res = json_decode(file_get_contents($url));
    return $res->modelYearHolder[0]->id;
}

function get_style_id($model_year_id)
{
    $key = "";
    $url = "http://api.edmunds.com/v1/api/vehicle/stylerepository/findstylesbymodelyearid?api_key=$key&modelyearid=$model_year_id&fmt=json";
    $res = json_decode(file_get_contents($url));
    return $res->styleHolder[0]->id;

}

Route::get(
    '/edmunds',
    function () {
        return get_tmv(get_style_id(get_model_year_id('ford', 'escape-hybrid', '2008')));
    }
);