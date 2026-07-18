<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $statusMessage;
    public bool $isNewOrder;

    /**
     * @param Order $order
     * @param bool $isNewOrder - true when order is first placed (sends BCC to shop)
     */
    public function __construct(Order $order, bool $isNewOrder = false)
    {
        $this->order = $order;
        $this->isNewOrder = $isNewOrder;
        $this->statusMessage = match ($order->status) {
            'confirmed' => 'Your order has been confirmed! We are preparing it for shipment.',
            'processing' => 'Your order is being processed and will be shipped soon.',
            'shipped' => 'Great news! Your order has been shipped.' . ($order->tracking_number ? " Tracking: {$order->tracking_number}" : ''),
            'delivered' => 'Your order has been delivered. Thank you for shopping with Shivara!',
            default => 'Your order status has been updated.',
        };
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->order->status) {
            'confirmed' => 'Order Confirmed - ' . $this->order->order_number,
            'processing' => 'Order Processing - ' . $this->order->order_number,
            'shipped' => 'Order Shipped - ' . $this->order->order_number,
            'delivered' => 'Order Delivered - ' . $this->order->order_number,
            default => 'Order Update - ' . $this->order->order_number,
        };

        // Only BCC admin when it's a NEW order received
        $bcc = [];
        if ($this->isNewOrder) {
            $bcc[] = new Address('shop@theshivara.com', 'Shivara Orders');
        }

        return new Envelope(
            subject: $subject,
            bcc: $bcc,
            replyTo: [
                new Address('shop@theshivara.com', 'Shivara'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status');
    }
}
