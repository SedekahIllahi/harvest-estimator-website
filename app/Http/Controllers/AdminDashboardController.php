<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Mock data until we hook up the real Eloquent queries
        $stats = [
            'total_farmers' => 142,
            'active_fields' => 89,
            'est_harvest_tons' => 450.5,
            'latest_price' => 'Rp 12,000/kg'
        ];

        // Assuming you might want to show recently registered fields on the map later
        $recent_mappings = [
            ['farmer' => 'Pak Budi', 'location' => 'Block A', 'area_ha' => 2.5, 'crop' => 'Rice'],
            ['farmer' => 'Bu Siti', 'location' => 'Block C', 'area_ha' => 1.2, 'crop' => 'Corn'],
        ];

        return view('admin.dashboard', compact('stats', 'recent_mappings'));
    }
}