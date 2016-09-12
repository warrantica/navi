<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Funds;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class FundController extends Controller
{

    public function show($name){
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
        'fullName' => $fullName,
        'navFrom' => $nodes->eq(1)->children()->eq(2)->text(),
        'navTo' => $nodes->eq(0)->children()->eq(2)->text(),
        'dateFrom' => $nodes->eq(1)->children()->eq(0)->text(),
        'dateTo' => $nodes->eq(0)->children()->eq(0)->text()
      ];
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
      $data = $html->filter('tr[bgcolor="#F2F2F2"]')->each(function(Crawler $node, $i){
        return [
          'x' => FundController::formatDate($node->children()->eq(0)->text()),
          'y' => $node->children()->eq(2)->text()
        ];
      });

      $result = [];

      $result[] = ['x' => $data[count($data)-1]['x'], 'y' => '1'];
      $baseNav = $data[count($data)-1]['y'];
      for($i=count($data)-2; $i>=0; --$i){
        $result[] = [
          'x' => $data[$i]['x'],
          'y' => $data[$i]['y']/$baseNav
        ];
      }

      return [
        'name' => $name,
        'fundData' => Funds::where('name', $name)->first(),
        'chartData' => $result
      ];
    }

    public function all(){
      return Funds::all();
    }

    private function getLatestDate(){
      $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      $request = $guzzle->post('/AIMC/aimc_navCenter.jsp');
      $html = new Crawler((string)$request->getBody());

      return Carbon::create(
        $html->filter('select[name="data_year"] option[selected]')->attr('value') - 543,
        $html->filter('select[name="data_month"] option[selected]')->attr('value'),
        $html->filter('select[name="data_date"] option[selected]')->attr('value')
      );
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
}
