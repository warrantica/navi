<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fund extends Model
{

    protected $dates = [
      'created_at',
      'updated_at',
      'dateFrom',
      'dateTo',
      'refreshDate'
    ];

    public function getCacheIsUsableAttribute(){
      return ($this->refreshDate !== null
        && $this->refreshDate->isSameDay(Carbon::now()))
        ? true : false;
    }
}
