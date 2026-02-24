<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'customer_id',
        'assigned_to',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TKT-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Technician::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function getCategoryLabelAttribute()
    {
        return [
            'billing' => 'Billing',
            'technical' => 'Technical',
            'installation' => 'Installation',
            'complaint' => 'Complaint',
            'inquiry' => 'Inquiry',
            'other' => 'Other',
        ][$this->category] ?? $this->category;
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'yellow',
            'urgent' => 'red',
        ][$this->priority] ?? 'gray';
    }

    public function getStatusColorAttribute()
    {
        return [
            'open' => 'blue',
            'in_progress' => 'yellow',
            'waiting_customer' => 'purple',
            'resolved' => 'green',
            'closed' => 'gray',
        ][$this->status] ?? 'gray';
    }
}
