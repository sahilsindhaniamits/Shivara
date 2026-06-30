import { NextRequest, NextResponse } from "next/server";
import {
  createShiprocketOrder,
  generateAWB,
  trackShipment,
  checkServiceability,
} from "@/lib/shiprocket";

// Check delivery serviceability
export async function GET(req: NextRequest) {
  try {
    const pincode = req.nextUrl.searchParams.get("pincode");
    const weight = req.nextUrl.searchParams.get("weight") || "500";

    if (!pincode) {
      return NextResponse.json(
        { error: "Pincode is required" },
        { status: 400 }
      );
    }

    const result = await checkServiceability(pincode, Number(weight));

    return NextResponse.json({
      available: result?.data?.available_courier_companies?.length > 0,
      couriers: result?.data?.available_courier_companies || [],
      estimatedDays: result?.data?.available_courier_companies?.[0]?.estimated_delivery_days,
    });
  } catch (error) {
    console.error("Serviceability check failed:", error);
    return NextResponse.json(
      { error: "Failed to check serviceability" },
      { status: 500 }
    );
  }
}

// Create shipment (Shiprocket or Manual)
export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const { method, orderId, ...shippingData } = body;

    if (method === "shiprocket") {
      const result = await createShiprocketOrder(shippingData);

      if (result.order_id) {
        // Generate AWB
        const awbResult = await generateAWB(result.shipment_id);

        return NextResponse.json({
          success: true,
          shiprocketOrderId: result.order_id,
          shipmentId: result.shipment_id,
          awbCode: awbResult?.response?.data?.awb_code,
          courierName: awbResult?.response?.data?.courier_name,
        });
      }

      return NextResponse.json({ error: result.message }, { status: 400 });
    } else {
      // Manual shipping - just update tracking info
      return NextResponse.json({
        success: true,
        method: "manual",
        trackingNumber: shippingData.trackingNumber,
        courierName: shippingData.courierName,
      });
    }
  } catch (error) {
    console.error("Shipment creation failed:", error);
    return NextResponse.json(
      { error: "Failed to create shipment" },
      { status: 500 }
    );
  }
}
