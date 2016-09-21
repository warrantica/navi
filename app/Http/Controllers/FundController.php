<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Fund;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class FundController extends Controller
{

    public function show($name){
      $fund = Fund::where('name', $name)->first();
      if($fund !== null){
        if($fund->cache_is_usable){
          return $fund;
        }else{
          $data = FundController::fetchFundData($name);
          $fund->navFrom = $data['navFrom'];
          $fund->nav = $data['nav'];
          $fund->dateFrom = $data['dateFrom'];
          $fund->dateTo = $data['dateTo'];
          $fund->refreshDate = Carbon::now();
          $fund->save();

          return $data;
        }
      }else{
        return FundController::fetchFundData($name);
      }
    }

    public function chart($name, $numberOfMonths){
      $date = FundController::getLatestDate();
      $dateFrom = FundController::getDateFrom($date, $numberOfMonths);

      $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      $request = $guzzle->post('/AIMC/aimc_navSearchResult.jsp',['form_params'=>[
        'searchType' => 'oldFund',
        'abbrName' => $name,
        'data_month' => $dateFrom->month,
        'data_year' => $dateFrom->year + 543,
        'data_month2' => $date->month,
        'data_year2' => $date->year + 543
      ]]);

      $html = new Crawler((string)$request->getBody());

      $data = [];
      $data = $html->filter('tr[bgcolor="#F2F2F2"]')
        ->each(function(Crawler $node, $i) use ($dateFrom){
          $x = FundController::dateToCarbon($node->children()->eq(0)->text());
          if($x->lt($dateFrom)) return false;
          return [
            'x' => $x->toDateString(),
            'y' => $node->children()->eq(2)->text()
          ];
        }
      );

      //get rid of falsey values in previous statement using default callback
      //of array_filter - clever eh!
      $data = array_filter($data);

      $result = [];

      $result[] = ['x' => $data[count($data)-1]['x'], 'y' => '0'];
      $baseNav = $data[count($data)-1]['y'];
      for($i=count($data)-2; $i>=0; --$i){
        $result[] = [
          'x' => $data[$i]['x'],
          'y' => (($data[$i]['y']/$baseNav) - 1) * 100
        ];
      }

      $fund = Fund::where('name', $name)->first();
      if($fund === null){
        $fund = new Fund([
          'name' => $name,
          'color' => '#FFFFFF'
        ]);
      }

      return [
        'name' => $name,
        'fundData' => $fund,
        'chartData' => $result
      ];
    }

    public function all(){
      return Fund::all();
    }

    public function add(Request $request){
      dd($request->name);

      // $fund = new Fund([
      //   'name' => $request->name,
      //   'color' => $request->color
      // ])->save();
    }

    private function fetchFundData($name){
      $date = FundController::getLatestDate();
      $dateFrom = FundController::getDateFrom($date, 1);

      $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      $request = $guzzle->post('/AIMC/aimc_navSearchResult.jsp',['form_params'=>[
        'searchType' => 'oldFund',
        'abbrName' => $name,
        'data_month' => $dateFrom->month,
        'data_year' => $dateFrom->year + 543,
        'data_month2' => $date->month,
        'data_year2' => $date->year + 543
      ]]);

      $html = new Crawler((string)$request->getBody());
      $fullName = $html->filter('.header center')->text();
      $nodes = $html->filter('tr[bgcolor="#F2F2F2"]');
      return [
        'name' => $name,
        'fullName' => $fullName,
        'navFrom' => $nodes->eq(1)->children()->eq(2)->text(),
        'nav' => $nodes->eq(0)->children()->eq(2)->text(),
        'dateFrom' => FundController::dateToCarbon($nodes->eq(1)->children()->eq(0)->text())->toDateTimeString(),
        'dateTo' => FundController::dateToCarbon($nodes->eq(0)->children()->eq(0)->text())->toDateTimeString()
      ];
    }

    private function getLatestDate(){
      return Carbon::now()->subDay();

      // $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      // $request = $guzzle->post('/AIMC/aimc_navCenter.jsp');
      // $html = new Crawler((string)$request->getBody());
      //
      // return Carbon::create(
      //   $html->filter('select[name="data_year"] option[selected]')->attr('value') - 543,
      //   $html->filter('select[name="data_month"] option[selected]')->attr('value'),
      //   $html->filter('select[name="data_date"] option[selected]')->attr('value')
      // );
    }

    private function getDateFrom(Carbon $date, $numberOfMonths){
      return (new Carbon($date))->subMonths($numberOfMonths);
    }

    private function formatDate($date){
      $dateArr = explode("/", $date);

      $dateArr[2] -= 543;
      if(strlen($dateArr[0]) < 2) $dateArr[0] = "0" . $dateArr[0];
      if(strlen($dateArr[1]) < 2) $dateArr[1] = "0" . $dateArr[1];

      return $dateArr[2] . "-" . $dateArr[1] . "-" . $dateArr[0];
    }

    private function dateToCarbon($date){
      $dateArr = explode("/", $date);
      $dateArr[2] -= 543;

      return Carbon::createFromDate($dateArr[2], $dateArr[1], $dateArr[0]);
    }
}
