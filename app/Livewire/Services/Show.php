<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Service $service;
    public $uptimePercentage;
    public $averageLatency;
    public $latencyChartData;

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->calculateMetrics();
    }

    public function calculateMetrics()
    {
        $total = $this->service->checkHistories()->count();
        $up = $this->service->checkHistories()->where('status', 'up')->count();
        $this->uptimePercentage = $total > 0 ? round(($up / $total) * 100, 2) : 0;
        
        $this->averageLatency = round($this->service->checkHistories()->whereNotNull('response_time_ms')->avg('response_time_ms') ?? 0, 2);

        $histories = $this->service->checkHistories()->latest()->take(60)->get()->reverse();
        
        $labels = [];
        $data = [];
        $colors = [];
        
        foreach ($histories as $h) {
            $labels[] = $h->created_at->format('H:i');
            $data[] = $h->response_time_ms ?: 0;
            $colors[] = $h->status === 'up' ? '#10B981' : '#EF4444';
        }

        $this->latencyChartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Latency (ms) over time',
                    'data' => $data,
                    'borderColor' => '#6366F1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => $colors,
                    'pointBorderColor' => $colors,
                    'pointRadius' => 4,
                ]
            ]
        ];
    }

    public function render()
    {
        $logs = $this->service->checkHistories()->latest()->paginate(20);
        
        return view('livewire.services.show', [
            'logs' => $logs
        ])->layout('layouts.app');
    }
}
