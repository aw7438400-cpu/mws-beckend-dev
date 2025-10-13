<?php

namespace App\Traits;

use App\Models\SystemControl;

trait HasSystemControl
{
    public function getCurrentCompanyId()
    {
        return auth()->user()->company_id;
    }

    public function getSystemControl()
    {
        $companyId = $this->getCurrentCompanyId();
        return SystemControl::where('company_id', $companyId)->first();
    }
}
