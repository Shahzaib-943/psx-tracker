<?php

namespace App\Livewire\Calculators;

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
        $this->calculate();
    }

    public function updated()
    {
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
        return view('livewire.calculators.dividend-calculator');
    }
}
