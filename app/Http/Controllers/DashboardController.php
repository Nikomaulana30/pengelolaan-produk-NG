<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerComplaint;
use App\Models\DokumenRetur;
use App\Models\WarehouseVerification;
use App\Models\QualityReinspection;
use App\Models\ProductionRework;
use App\Models\FinalQualityCheck;
use App\Models\ReturnShipment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->role;

        // Route to appropriate dashboard based on role
        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'staff_exim':
                return $this->staffEximDashboard();
            case 'supervisor_warehouse':
                return $this->warehouseDashboard();
            case 'manager_production':
                return $this->productionDashboard();
            case 'manager_quality':
                return $this->qualityDashboard();
            default:
                abort(403, 'Role tidak valid atau tidak memiliki akses ke dashboard.');
        }
    }

    private function adminDashboard()
    {
        $data = [
            'total_users' => User::count(),
            'total_complaints' => CustomerComplaint::count(),
            'total_reworks' => ProductionRework::count(),
            'total_shipments' => ReturnShipment::count(),
            'pending_complaints' => CustomerComplaint::whereIn('status', ['draft', 'submitted'])->count(),
            'in_progress' => CustomerComplaint::where('status', 'processing')->count(),
            'completed' => CustomerComplaint::where('status', 'completed')->count(),
            'recent_complaints' => CustomerComplaint::with('staffExim')->latest()->take(5)->get(),
            'user_stats' => User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get(),
        ];

        return view('dashboards.admin', compact('data'));
    }

    private function staffEximDashboard()
    {
        $userId = Auth::id();
        
        $data = [
            'my_complaints' => CustomerComplaint::where('staff_exim_id', $userId)->count(),
            'total_complaints' => CustomerComplaint::count(),
            'pending_review' => DokumenRetur::where('status', 'draft')->count(),
            'in_progress' => CustomerComplaint::where('status', 'processing')->count(),
            'completed_today' => CustomerComplaint::where('status', 'completed')
                ->whereDate('updated_at', today())->count(),
            'recent_assignments' => CustomerComplaint::where('staff_exim_id', $userId)
                ->latest()->take(5)->get(),
            'dokumen_retur_pending' => DokumenRetur::where('status', 'draft')
                ->with('customerComplaint')->latest()->take(5)->get(),
        ];

        return view('dashboards.staff-exim', compact('data'));
    }

    private function warehouseDashboard()
    {
        $userId = Auth::id();
        
        $data = [
            'pending_verification' => WarehouseVerification::where('status', 'draft')->count(),
            'verified_today' => WarehouseVerification::where('status', 'verified')
                ->whereDate('updated_at', today())->count(),
            'pending_shipment' => ReturnShipment::where('status', 'draft')->count(),
            'shipped_today' => ReturnShipment::whereIn('status_pengiriman', ['shipped', 'delivered'])
                ->whereDate('created_at', today())->count(),
            'recent_verifications' => WarehouseVerification::with('dokumenRetur.customerComplaint')
                ->latest()->take(5)->get(),
            'pending_shipments' => ReturnShipment::where('status', 'draft')
                ->with('finalQualityCheck')->latest()->take(5)->get(),
        ];

        return view('dashboards.warehouse', compact('data'));
    }

    private function productionDashboard()
    {
        $data = [
            'pending_rework' => ProductionRework::where('status', 'draft')->count(),
            'in_progress_rework' => ProductionRework::where('status', 'in_progress')->count(),
            'completed_today' => ProductionRework::where('status', 'completed')
                ->whereDate('tanggal_selesai_rework', today())->count(),
            'total_cost_month' => ProductionRework::whereMonth('created_at', now()->month)
                ->sum('actual_biaya'),
            'recent_reworks' => ProductionRework::with('qualityReinspection')
                ->latest()->take(5)->get(),
            'rework_by_status' => ProductionRework::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')->get(),
        ];

        return view('dashboards.production', compact('data'));
    }

    private function qualityDashboard()
    {
        $data = [
            'pending_inspection' => QualityReinspection::where('status', 'draft')->count(),
            'inspected_today' => QualityReinspection::where('status', 'inspected')
                ->whereDate('updated_at', today())->count(),
            'pending_final_check' => FinalQualityCheck::where('status', 'draft')->count(),
            'passed_today' => FinalQualityCheck::where('keputusan_final', 'approved_for_shipment')
                ->whereDate('created_at', today())->count(),
            'recent_inspections' => QualityReinspection::with('warehouseVerification.dokumenRetur.customerComplaint')
                ->latest()->take(5)->get(),
            'defect_distribution' => QualityReinspection::selectRaw('jenis_defect, COUNT(*) as count')
                ->groupBy('jenis_defect')->take(5)->get(),
        ];

        return view('dashboards.quality', compact('data'));
    }
}
