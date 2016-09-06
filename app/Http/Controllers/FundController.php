<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class FundController extends Controller
{
    public function show($name){
      $date = FundController::getLatestDate();

      $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      $request = $guzzle->post('/AIMC/aimc_navSearchResult.jsp',['form_params'=>[
        'searchType' => 'abbr_name',
        'fundupmullist2' => $name,
        'data_date' => $date['date'],
        'data_month' => $date['month'],
        'data_year' => $date['year']
      ]]);

      $html = new Crawler((string)$request->getBody());
      $nodes = $html->filter('tr[bgcolor="#F2F2F2"]')->children();

      return [
        'name' => $nodes->eq(0)->text(),
        'nav' => $nodes->eq(2)->text()
      ];
    }

    public function history($name){
      $date = FundController::getLatestDate();

      $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      $request = $guzzle->post('/AIMC/aimc_navSearchResult.jsp',['form_params'=>[
        'searchType' => 'oldFund',
        'abbrName' => $name,
        'data_month' => $date['month']-1,
        'data_year' => $date['year'],
        'data_month2' => $date['month'],
        'data_year2' => $date['year']
      ]]);

      $html = new Crawler((string)$request->getBody());

      $result = [];
      $result[] = $html->filter('tr[bgcolor="#F2F2F2"]')->each(function(Crawler $node, $i){
        return [
          'date' => $node->children()->eq(0)->text(),
          'nav' => $node->children()->eq(2)->text()
        ];
      });

      return $result;
    }

    public function getLatestDate(){
      $guzzle = new Client(['base_uri' => 'http://www.thaimutualfund.com']);
      $request = $guzzle->post('/AIMC/aimc_navCenter.jsp');
      $html = new Crawler((string)$request->getBody());

      return [
        'date' => $html->filter('select[name="data_date"] option[selected]')->attr('value'),
        'month' => $html->filter('select[name="data_month"] option[selected]')->attr('value'),
        'year' => $html->filter('select[name="data_year"] option[selected]')->attr('value')
      ];
    }
}
