<?php

namespace App\Services\AbcDemo;

class RuleNameService
{
    protected $ruleNameRe;

    public function __construct(
        RuleNameRepository $ruleNameRe
    ) {
        $this->ruleNameRe = $ruleNameRe;
    }
}
