<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for order
     */
    public static function generateOrderQRCode(Order $order)
    {
        // Generate QR code data
        $qrData = $order->getQRCodeData();
        $qrString = json_encode($qrData);
        
        // For now, we'll return the QR code string
        // In a real implementation, you would use a QR code library like SimpleSoftwareIO/simple-qrcode
        return $qrString;
    }

    /**
     * Generate QR code image and save to storage
     */
    public static function generateQRCodeImage(Order $order, $size = 200)
    {
        $qrData = $order->getQRCodeData();
        $qrString = json_encode($qrData);
        
        // Create a simple QR code representation using a URL
        // In production, you would use a proper QR code library
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($qrString);
        
        return $qrCodeUrl;
    }

    /**
     * Generate QR code for order confirmation
     */
    public static function generateOrderConfirmationQR(Order $order)
    {
        $confirmationData = [
            'type' => 'order_confirmation',
            'order_number' => $order->order_number,
            'qrcode' => $order->qrcode,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'tickets_count' => $order->getTicketsCount(),
            'event_name' => $order->tickets->first()->event->name ?? 'Unknown Event',
            'purchase_date' => $order->created_at->format('Y-m-d H:i:s'),
        ];

        return json_encode($confirmationData);
    }

    /**
     * Get QR code display data
     */
    public static function getQRCodeDisplayData(Order $order)
    {
        return [
            'qr_code' => $order->qrcode,
            'qr_data' => self::generateOrderConfirmationQR($order),
            'qr_image_url' => self::generateQRCodeImage($order),
            'order_number' => $order->order_number,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'tickets_count' => $order->getTicketsCount(),
        ];
    }
}





