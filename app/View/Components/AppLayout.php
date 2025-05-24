<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.app', [
            'churchColors' => [
                'primary' => '#3B82F6',    // Blue
                'secondary' => '#10B981',   // Green
                'accent' => '#F59E0B',      // Amber
                'complementary' => [
                    '#1E40AF',             // Dark Blue
                    '#475569',             // Slate
                    '#14B8A6',             // Light Teal
                    '#F5F5F4',             // Linen
                ],
            ],
        ]);
    }
} 