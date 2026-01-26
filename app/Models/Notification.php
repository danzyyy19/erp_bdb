<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Scope for unread
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            // SPK
            'spk_pending' => 'clipboard-list',
            'spk_approved' => 'check-circle',
            'spk_rejected' => 'x-circle',
            'spk_started' => 'play',
            'spk_completed' => 'check-square',
            // FPB
            'fpb_pending' => 'file-text',
            'fpb_approved' => 'check-circle',
            'fpb_rejected' => 'x-circle',
            // Invoice
            'invoice_pending' => 'file-invoice',
            'invoice_approved' => 'check-circle',
            'invoice_rejected' => 'x-circle',
            'invoice_paid' => 'banknote',
            'invoice_overdue' => 'alert-circle',
            // PO
            'po_pending' => 'shopping-cart',
            'po_approved' => 'check-circle',
            'po_rejected' => 'x-circle',
            'po_received' => 'package-check',
            // Delivery Note
            'dn_created' => 'truck',
            'dn_approved' => 'check-circle',
            'dn_delivered' => 'map-pin',
            // Job Cost
            'job_cost_pending' => 'calculator',
            'job_cost_approved' => 'check-circle',
            'job_cost_rejected' => 'x-circle',
            // Stock
            'low_stock' => 'alert-triangle',
            'stock_added' => 'package-plus',
            // General
            default => 'bell',
        };
    }

    /**
     * Get type color
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'spk_approved', 'fpb_approved', 'invoice_approved', 'po_approved',
            'job_cost_approved', 'dn_approved', 'invoice_paid', 'po_received',
            'dn_delivered', 'spk_completed', 'stock_added' => 'green',

            'spk_rejected', 'fpb_rejected', 'invoice_rejected', 'po_rejected',
            'job_cost_rejected', 'invoice_overdue', 'low_stock' => 'red',

            'spk_pending', 'fpb_pending', 'invoice_pending', 'po_pending',
            'job_cost_pending' => 'yellow',

            'spk_started', 'dn_created' => 'blue',

            default => 'gray',
        };
    }

    // ===================== SPK NOTIFICATIONS =====================

    /**
     * SPK menunggu approval - ke Owner
     */
    public static function notifySpkPending(Spk $spk): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'spk_pending',
                'title' => 'SPK Menunggu Approval',
                'message' => "SPK {$spk->spk_number} dibuat oleh {$spk->creator->name} dan menunggu persetujuan Anda.",
                'data' => ['spk_id' => $spk->id, 'spk_uuid' => $spk->uuid],
            ]);
        }
    }

    /**
     * SPK disetujui - ke Creator
     */
    public static function notifySpkApproved(Spk $spk): void
    {
        self::create([
            'user_id' => $spk->created_by,
            'type' => 'spk_approved',
            'title' => 'SPK Disetujui',
            'message' => "SPK {$spk->spk_number} telah disetujui oleh {$spk->approver->name}. Produksi dapat dimulai.",
            'data' => ['spk_id' => $spk->id, 'spk_uuid' => $spk->uuid],
        ]);
    }

    /**
     * SPK ditolak - ke Creator
     */
    public static function notifySpkRejected(Spk $spk): void
    {
        self::create([
            'user_id' => $spk->created_by,
            'type' => 'spk_rejected',
            'title' => 'SPK Ditolak',
            'message' => "SPK {$spk->spk_number} ditolak oleh {$spk->approver->name}.",
            'data' => ['spk_id' => $spk->id, 'spk_uuid' => $spk->uuid],
        ]);
    }

    /**
     * SPK mulai produksi - ke Owner
     */
    public static function notifySpkStarted(Spk $spk): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'spk_started',
                'title' => 'Produksi Dimulai',
                'message' => "Produksi SPK {$spk->spk_number} telah dimulai.",
                'data' => ['spk_id' => $spk->id, 'spk_uuid' => $spk->uuid],
            ]);
        }
    }

    /**
     * SPK selesai - ke Owner
     */
    public static function notifySpkCompleted(Spk $spk): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'spk_completed',
                'title' => 'Produksi Selesai',
                'message' => "SPK {$spk->spk_number} telah selesai diproduksi.",
                'data' => ['spk_id' => $spk->id, 'spk_uuid' => $spk->uuid],
            ]);
        }
    }

    // ===================== FPB NOTIFICATIONS =====================

    /**
     * FPB menunggu approval - ke Owner
     */
    public static function notifyFpbPending(Fpb $fpb): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'fpb_pending',
                'title' => 'FPB Menunggu Approval',
                'message' => "FPB {$fpb->fpb_number} menunggu persetujuan Anda.",
                'data' => ['fpb_id' => $fpb->id],
            ]);
        }
    }

    /**
     * FPB disetujui - ke Creator
     */
    public static function notifyFpbApproved(Fpb $fpb): void
    {
        self::create([
            'user_id' => $fpb->created_by,
            'type' => 'fpb_approved',
            'title' => 'FPB Disetujui',
            'message' => "FPB {$fpb->fpb_number} telah disetujui. Stok akan dikurangi.",
            'data' => ['fpb_id' => $fpb->id],
        ]);
    }

    /**
     * FPB ditolak - ke Creator
     */
    public static function notifyFpbRejected(Fpb $fpb): void
    {
        self::create([
            'user_id' => $fpb->created_by,
            'type' => 'fpb_rejected',
            'title' => 'FPB Ditolak',
            'message' => "FPB {$fpb->fpb_number} ditolak.",
            'data' => ['fpb_id' => $fpb->id],
        ]);
    }

    // ===================== INVOICE NOTIFICATIONS =====================

    /**
     * Invoice menunggu approval - ke Owner
     */
    public static function notifyInvoicePending(Invoice $invoice): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'invoice_pending',
                'title' => 'Invoice Menunggu Approval',
                'message' => "Invoice {$invoice->invoice_number} (Rp " . number_format($invoice->total, 0, ',', '.') . ") menunggu persetujuan.",
                'data' => ['invoice_id' => $invoice->id, 'invoice_uuid' => $invoice->uuid],
            ]);
        }
    }

    /**
     * Invoice disetujui - ke Finance
     */
    public static function notifyInvoiceApproved(Invoice $invoice): void
    {
        $finances = User::where('role', 'finance')->get();
        foreach ($finances as $finance) {
            self::create([
                'user_id' => $finance->id,
                'type' => 'invoice_approved',
                'title' => 'Invoice Disetujui',
                'message' => "Invoice {$invoice->invoice_number} telah disetujui dan siap untuk penagihan.",
                'data' => ['invoice_id' => $invoice->id, 'invoice_uuid' => $invoice->uuid],
            ]);
        }
    }

    /**
     * Invoice dibayar - ke Owner & Finance
     */
    public static function notifyInvoicePaid(Invoice $invoice): void
    {
        $users = User::whereIn('role', ['owner', 'finance'])->get();
        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'type' => 'invoice_paid',
                'title' => 'Pembayaran Diterima',
                'message' => "Invoice {$invoice->invoice_number} telah lunas (Rp " . number_format($invoice->total, 0, ',', '.') . ").",
                'data' => ['invoice_id' => $invoice->id, 'invoice_uuid' => $invoice->uuid],
            ]);
        }
    }

    // ===================== PO NOTIFICATIONS =====================

    /**
     * PO menunggu approval - ke Owner
     */
    public static function notifyPoPending(Purchase $po): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'po_pending',
                'title' => 'PO Menunggu Approval',
                'message' => "PO {$po->purchase_number} (Rp " . number_format($po->total_amount, 0, ',', '.') . ") menunggu persetujuan.",
                'data' => ['po_id' => $po->id, 'po_uuid' => $po->uuid],
            ]);
        }
    }

    /**
     * PO disetujui - ke Finance
     */
    public static function notifyPoApproved(Purchase $po): void
    {
        $finances = User::where('role', 'finance')->get();
        foreach ($finances as $finance) {
            self::create([
                'user_id' => $finance->id,
                'type' => 'po_approved',
                'title' => 'PO Disetujui',
                'message' => "PO {$po->purchase_number} disetujui. Silakan lakukan pembelian ke {$po->supplier->name}.",
                'data' => ['po_id' => $po->id, 'po_uuid' => $po->uuid],
            ]);
        }
    }

    /**
     * PO barang diterima - ke Owner
     */
    public static function notifyPoReceived(Purchase $po): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'po_received',
                'title' => 'Barang PO Diterima',
                'message' => "Barang dari PO {$po->purchase_number} telah diterima dan stok diperbarui.",
                'data' => ['po_id' => $po->id, 'po_uuid' => $po->uuid],
            ]);
        }
    }

    // ===================== DELIVERY NOTE NOTIFICATIONS =====================

    /**
     * Delivery Note dibuat - ke Owner & Finance
     */
    public static function notifyDeliveryNoteCreated(DeliveryNote $dn): void
    {
        $users = User::whereIn('role', ['owner', 'finance'])->get();
        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'type' => 'dn_created',
                'title' => 'Surat Jalan Baru',
                'message' => "Surat Jalan {$dn->sj_number} untuk {$dn->customer->name} telah dibuat.",
                'data' => ['dn_id' => $dn->id, 'dn_uuid' => $dn->uuid],
            ]);
        }
    }

    /**
     * Delivery Note dikirim - ke Finance
     */
    public static function notifyDeliveryNoteDelivered(DeliveryNote $dn): void
    {
        $finances = User::where('role', 'finance')->get();
        foreach ($finances as $finance) {
            self::create([
                'user_id' => $finance->id,
                'type' => 'dn_delivered',
                'title' => 'Barang Terkirim',
                'message' => "Surat Jalan {$dn->sj_number} telah dikirim ke {$dn->customer->name}.",
                'data' => ['dn_id' => $dn->id, 'dn_uuid' => $dn->uuid],
            ]);
        }
    }

    // ===================== JOB COST NOTIFICATIONS =====================

    /**
     * Job Cost menunggu approval - ke Owner
     */
    public static function notifyJobCostPending($jobCost): void
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            self::create([
                'user_id' => $owner->id,
                'type' => 'job_cost_pending',
                'title' => 'Job Cost Menunggu Approval',
                'message' => "Job Cost {$jobCost->job_cost_number} menunggu persetujuan.",
                'data' => ['job_cost_id' => $jobCost->id],
            ]);
        }
    }

    /**
     * Job Cost disetujui - ke Creator
     */
    public static function notifyJobCostApproved($jobCost): void
    {
        self::create([
            'user_id' => $jobCost->created_by,
            'type' => 'job_cost_approved',
            'title' => 'Job Cost Disetujui',
            'message' => "Job Cost {$jobCost->job_cost_number} telah disetujui.",
            'data' => ['job_cost_id' => $jobCost->id],
        ]);
    }

    // ===================== STOCK NOTIFICATIONS =====================

    /**
     * Stok rendah - ke Owner & Operasional
     */
    public static function notifyLowStock(Product $product): void
    {
        $users = User::whereIn('role', ['owner', 'operasional'])->get();
        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'type' => 'low_stock',
                'title' => 'Peringatan Stok Rendah',
                'message' => "Stok {$product->name} ({$product->code}) tersisa {$product->current_stock} {$product->unit} (minimum: {$product->minimum_stock}).",
                'data' => ['product_id' => $product->id],
            ]);
        }
    }
}
