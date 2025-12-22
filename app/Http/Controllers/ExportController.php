<?php
namespace App\Http\Controllers;

use App\Exports\OperatorKpiExport;
use App\Exports\MachineKpiExport;
use App\Exports\DowntimeExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function operatorKpi(string $date)
    {
        return Excel::download(
            new OperatorKpiExport($date),
            "kpi_operator_{$date}.xlsx"
        );
    }

    public function machineKpi(string $date)
{
    return Excel::download(
        new MachineKpiExport($date),
        "kpi_machine_{$date}.xlsx"
    );
}
public function downtime(string $date)
{
    return \Maatwebsite\Excel\Facades\Excel::download(
        new DowntimeExport($date),
        "downtime_{$date}.xlsx"
    );
}

}
