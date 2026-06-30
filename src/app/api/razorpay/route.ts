import { NextRequest, NextResponse } from "next/server";
import { createRazorpayOrder, verifyRazorpaySignature } from "@/lib/razorpay";

// Create Razorpay order
export async function POST(req: NextRequest) {
  try {
    const { amount, receipt } = await req.json();

    if (!amount || !receipt) {
      return NextResponse.json(
        { error: "Amount and receipt are required" },
        { status: 400 }
      );
    }

    const order = await createRazorpayOrder(amount, receipt);

    return NextResponse.json({
      orderId: order.id,
      amount: order.amount,
      currency: order.currency,
      keyId: process.env.RAZORPAY_KEY_ID,
    });
  } catch (error) {
    console.error("Razorpay order creation failed:", error);
    return NextResponse.json(
      { error: "Failed to create payment order" },
      { status: 500 }
    );
  }
}

// Verify payment
export async function PUT(req: NextRequest) {
  try {
    const { razorpay_order_id, razorpay_payment_id, razorpay_signature } =
      await req.json();

    const isValid = verifyRazorpaySignature(
      razorpay_order_id,
      razorpay_payment_id,
      razorpay_signature
    );

    if (!isValid) {
      return NextResponse.json(
        { error: "Invalid payment signature" },
        { status: 400 }
      );
    }

    // Update order status in database
    // await prisma.order.update(...)

    return NextResponse.json({ success: true, paymentId: razorpay_payment_id });
  } catch (error) {
    console.error("Payment verification failed:", error);
    return NextResponse.json(
      { error: "Payment verification failed" },
      { status: 500 }
    );
  }
}
