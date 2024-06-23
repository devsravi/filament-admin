<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countryIndia   = Country::where('country_code', 'IN')->withCount('employees')->first();
        $countryUSA   = Country::where('country_code', 'USA')->withCount('employees')->first();
        return [
            Stat::make('All Employees', Employee::count()),
            Stat::make($countryIndia->name?? ' ' . ' Employees', $countryIndia->employees_count??0),
            Stat::make($countryUSA->name?? ' ' . ' Employees', $countryUSA->employees_count?? 0),
        ];
    }
}
