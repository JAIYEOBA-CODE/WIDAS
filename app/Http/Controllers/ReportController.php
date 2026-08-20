<?php

namespace App\Http\Controllers;

use App\Services\ReportingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(
        private ReportingService $reportingService
    ) {}

    public function index()
    {
        return Inertia::render('Admin/Reports', [
            'daily' => $this->reportingService->generateDailyReport(),
            'weekly' => $this->reportingService->generateWeeklyReport(),
            'monthly' => $this->reportingService->generateMonthlyReport(),
        ]);
    }

    public function exportDaily()
    {
        $data = $this->reportingService->generateDailyReport();
        $pdf = Pdf::loadView('reports.security', ['report' => $data]);
        return $pdf->download('daily-security-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportWeekly()
    {
        $data = $this->reportingService->generateWeeklyReport();
        $pdf = Pdf::loadView('reports.security', ['report' => $data]);
        return $pdf->download('weekly-security-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportMonthly()
    {
        $data = $this->reportingService->generateMonthlyReport();
        $pdf = Pdf::loadView('reports.security', ['report' => $data]);
        return $pdf->download('monthly-security-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
