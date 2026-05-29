<?php

namespace App\Widgets;

use App\Models\Tip;
use App\Support\Filament\PanelAccess;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class CaseManagementWidget extends Widget
{
    protected string $view = 'filament.widgets.case-management';

    public static function canView(): bool
    {
        return PanelAccess::allows([
            'view_complaints',
            'manage_complaints',
            'assign_cases',
            'create_call_tips',
            'view_own_call_tips',
            'review_supervisor_call_tips',
            'review_director_call_tips',
            'manage_sub_city_call_tips',
            'manage_woreda_call_tips',
            'manage_call_tip_workflow',
        ]);
    }

    public function getPendingCount()
    {
        return $this->complaintCounts()['pending'];
    }

    public function getUrgentCount()
    {
        return $this->complaintCounts()['urgent'];
    }

    public function getSupervisorQueueCount()
    {
        return $this->tipQueueCounts()['supervisor'];
    }

    public function getDirectorQueueCount()
    {
        return $this->tipQueueCounts()['director'];
    }

    protected function complaintCounts(): array
    {
        static $counts = null;

        if ($counts !== null) {
            return $counts;
        }

        $row = \App\Models\Complaint::query()
            ->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count")
            ->selectRaw("SUM(CASE WHEN priority = 'high' AND status IN ('pending', 'assigned') THEN 1 ELSE 0 END) as urgent_count")
            ->first();

        return $counts = [
            'pending' => (int) ($row?->pending_count ?? 0),
            'urgent' => (int) ($row?->urgent_count ?? 0),
        ];
    }

    protected function tipQueueCounts(): array
    {
        static $counts = null;

        if ($counts !== null) {
            return $counts;
        }

        $rows = Tip::query()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->where('tip_source', Tip::SOURCE_CALL_CENTER)
            ->whereIn('status', [
                Tip::STATUS_PENDING_SUPERVISOR_REVIEW,
                Tip::STATUS_PENDING_DIRECTOR_REVIEW,
            ])
            ->groupBy('status')
            ->pluck('count', 'status');

        return $counts = [
            'supervisor' => (int) ($rows[Tip::STATUS_PENDING_SUPERVISOR_REVIEW] ?? 0),
            'director' => (int) ($rows[Tip::STATUS_PENDING_DIRECTOR_REVIEW] ?? 0),
        ];
    }
}
