<?php

namespace App\Models;

use Database\Factories\ComplaintFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'reference_no',
    'user_id',
    'department_id',
    'title',
    'description',
    'priority',
    'status',
    'admin_remarks',
])]
class Complaint extends Model
{
    /** @use HasFactory<ComplaintFactory> */
    use HasFactory;

    /**
     * Priority options.
     */
    public const PRIORITIES = ['Low', 'Medium', 'High'];

    /**
     * Status options.
     */
    public const STATUSES = ['Pending', 'In Progress', 'Resolved', 'Rejected'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }

    public function isResolved(): bool
    {
        return $this->status === 'Resolved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    /**
     * Generate a unique formatted reference number: CMP-YYYY-XXXXX
     */
    public static function generateReferenceNo(): string
    {
        $year = date('Y');
        $lastComplaint = static::whereYear('created_at', $year)
            ->latest('id')
            ->first();

        $nextNumber = 1;
        if ($lastComplaint && preg_match('/CMP-\d{4}-(\d+)/', $lastComplaint->reference_no, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return sprintf('CMP-%s-%04d', $year, $nextNumber);
    }

    /**
     * Filter query by search term, status, department, and priority.
     *
     * @param  Builder<Complaint>  $query
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $q, string $search) {
            $q->where(function (Builder $sub) use ($search) {
                $sub->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, function (Builder $q, string $status) {
            $q->where('status', $status);
        });

        $query->when($filters['department_id'] ?? null, function (Builder $q, string|int $deptId) {
            $q->where('department_id', $deptId);
        });

        $query->when($filters['priority'] ?? null, function (Builder $q, string $priority) {
            $q->where('priority', $priority);
        });
    }
}
