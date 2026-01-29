<?php

namespace App\Livewire\Calculators;

use Livewire\Component;

class BalanceCalculator extends Component
{
    public $currentBalance;
    public $packagePrice;
    public $taxRate = 15;
    public $requiredBalance;

    public function updated()
    {
        $this->calculate();
    }

    public function calculate()
    {
        if($this->packagePrice > $this->currentBalance) {
            $this->requiredBalance = round(($this->packagePrice - $this->currentBalance)/ (1 - ($this->taxRate/100)), 2);
        } else {
            $this->requiredBalance = 0;
        }
    }
    public function render()
    {
        return view('livewire.calculators.balance-calculator');
    }
}
