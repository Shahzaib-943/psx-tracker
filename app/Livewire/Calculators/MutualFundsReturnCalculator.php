<?php

namespace App\Livewire\Calculators;

use Livewire\Component;

class MutualFundsReturnCalculator extends Component
{
    public $amountInvested;
    public $buyNav;
    public $currentNav;
    public $return = 0;
    public $returnPercent = 0;
    public $netAmount = 0;
    public function render()
    {
        return view('livewire.calculators.mutual-funds-return-calculator');
    }
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
        if (is_numeric($this->amountInvested) && is_numeric($this->buyNav) && is_numeric($this->currentNav)) {
            $units = $this->amountInvested / $this->buyNav;
            $currentValue = $units * $this->currentNav;
            $return = $currentValue - $this->amountInvested;
            $this->returnPercent = ($return / $this->amountInvested) * 100;
            $this->return = round($return, 2);
            $this->netAmount = round($currentValue, 2);
        } else {
            $this->return = 0;
            $this->returnPercent = 0;
            $this->netAmount = 0;
        }
    }
}
