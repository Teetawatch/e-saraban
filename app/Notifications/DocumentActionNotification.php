<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Document;
use App\Models\User;

class DocumentActionNotification extends Notification
{
    use Queueable;

    protected $document;
    protected $sender;
    protected $action;
    protected $note;

    /**
     * Create a new notification instance.
     * รับค่าข้อมูลเอกสารและผู้ส่งเข้ามา
     */
    public function __construct(Document $document, User $sender, string $action, ?string $note)
    {
        $this->document = $document;
        $this->sender = $sender;
        $this->action = $action;
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     * เลือกช่องทางการแจ้งเตือน (ในที่นี้ใช้ database)
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * กำหนดข้อมูลที่จะบันทึกลงในตาราง notifications
     */
    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_no' => $this->document->document_no,
            'title' => $this->document->title,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'action' => $this->action, // send, close, etc.
            'note' => $this->note,
            'url' => route('documents.show', $this->document->id), // ลิงก์ไปยังหน้าเอกสาร
        ];
    }
}