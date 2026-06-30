import Razorpay from "razorpay";

let razorpayInstance: Razorpay | null = null;

function getRazorpay() {
  if (!razorpayInstance) {
    razorpayInstance = new Razorpay({
      key_id: process.env.RAZORPAY_KEY_ID!,
      key_secret: process.env.RAZORPAY_KEY_SECRET!,
    });
  }
  return razorpayInstance;
}

export async function createRazorpayOrder(amount: number, receipt: string) {
  const razorpay = getRazorpay();
  const order = await razorpay.orders.create({
    amount: Math.round(amount * 100), // Razorpay accepts amount in paise
    currency: "INR",
    receipt,
    notes: {
      store: "Shivara",
    },
  });
  return order;
}

export function verifyRazorpaySignature(
  orderId: string,
  paymentId: string,
  signature: string
): boolean {
  const crypto = require("crypto");
  const body = orderId + "|" + paymentId;
  const expectedSignature = crypto
    .createHmac("sha256", process.env.RAZORPAY_KEY_SECRET!)
    .update(body.toString())
    .digest("hex");
  return expectedSignature === signature;
}
