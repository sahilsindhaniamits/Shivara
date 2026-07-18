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

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->statusMessage = match ($order->status) {
            'pending' => 'Thank you for your order! We have received it and will confirm it shortly.',
            'confirmed' => 'Your order has been confirmed! We are preparing it for shipment.',
            'processing' => 'Your order is being processed and will be shipped soon.',
            'shipped' => 'Great news! Your order has been shipped.' . ($order->tracking_number ? " Tracking: {$order->tracking_number}" : ''),
            'out_for_delivery' => 'Your order is out for delivery today!',
            'delivered' => 'Your order has been delivered. Thank you for shopping with Shivara!',
            'cancelled' => 'Your order has been cancelled. If you have questions, please contact us.',
            default => 'Your order status has been updated.',
        };
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->order->status) {
            'pending' => 'Order Received - ' . $this->order->order_number,
            'confirmed' => 'Order Confirmed - ' . $this->order->order_number,
            'processing' => 'Order Processing - ' . $this->order->order_number,
            'shipped' => 'Order Shipped - ' . $this->order->order_number,
            'out_for_delivery' => 'Out for Delivery - ' . $this->order->order_number,
            'delivered' => 'Order Delivered - ' . $this->order->order_number,
            'cancelled' => 'Order Cancelled - ' . $this->order->order_number,
            default => 'Order Update - ' . $this->order->order_number,
        };

        return new Envelope(
            subject: $subject,
            bcc: [
                new Address('shop@theshivara.com', 'Shivara Orders'),
            ],
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
