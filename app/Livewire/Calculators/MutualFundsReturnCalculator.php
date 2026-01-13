<?php

namespace App\Livewire\Calculators;

use Livewire\Component;

class MutualFundsReturnCalculator extends Component
{
    public $amountInvested;
    public $buyNav;
    public $currentNav;
    public $profit = 0;
    public $profitPercent = 0;
    public $netAmount = 0;
    public function render()
    {
        return view('livewire.calculators.mutual-funds-return-calculator');
    }

    public function updated()
    {
        $this->calculate();
    }

    public function calculate()
    {
        if (
            is_numeric($this->amountInvested) && is_numeric($this->buyNav) && is_numeric($this->currentNav) && $this->buyNav > 0 &&
            $this->amountInvested > 0
        ) {
            $units = $this->amountInvested / $this->buyNav;
            $currentValue = $units * $this->currentNav;
            $profit = $currentValue - $this->amountInvested;
            $this->profitPercent = ($profit / $this->amountInvested) * 100;
            $this->profit = round($profit, 2);
            $this->netAmount = round($currentValue, 2);
        } else {
            $this->profit = 0;
            $this->profitPercent = 0;
            $this->netAmount = 0;
        }
    }
}
