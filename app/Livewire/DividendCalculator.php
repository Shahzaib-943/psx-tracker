<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class DividendCalculator extends Component
{

    public $shares;
    public $dividendPerShare;
    public $tax = 15;
    public $netDividend = 0;

    public function updating($property)
    {
        Log::info("here");
        $this->calculate();
    }

    public function updated()
    {
Log::info("hereeeeeee");
        $this->calculate();
    }

    public function calculate()
    {
        if (is_numeric($this->shares) && is_numeric($this->dividendPerShare) && is_numeric($this->tax)) {
            $gross = $this->shares * $this->dividendPerShare;
            $taxAmount = ($gross * $this->tax) / 100;
            $this->netDividend = round($gross - $taxAmount, 2);
        } else {
            $this->netDividend = 0;
        }
    }

    public function render()
    {
        // return view('livewire.dividend-calculator')->layout('layouts.guest');
        return view('livewire.dividend-calculator');
    }
}
