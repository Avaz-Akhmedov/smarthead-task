<?php

namespace App\Models;

use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'subject',
        'message',
        'status',
        'answered_at',
    ];

    protected $casts = [
        'status' => TicketStatusEnum::class
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function scopeFilter(Builder $query, array $filters)
    {
        return $query
            ->when($filters['status'] ?? null, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($filters['email'] ?? null, function ($q, $email) {
                $q->whereRelation('customer', 'email', 'LIKE', "%{$email}%");
            })
            ->when($filters['phone_number'] ?? null, function ($q, $phone) {
                $q->whereRelation('customer', 'phone_number', 'LIKE', "%{$phone}%");
            })
            ->when($filters['date_from'] ?? null, function ($q, $date) {
                $q->whereDate('created_at', '>=', $date);
            })
            ->when($filters['date_to'] ?? null, function ($q, $date) {
                $q->whereDate('created_at', '<=', $date);
            });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk(env('FILESYSTEM_DISK'));
    }

}
