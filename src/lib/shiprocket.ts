const SHIPROCKET_BASE_URL = "https://apiv2.shiprocket.in/v1/external";

let shiprocketToken: string | null = null;
let tokenExpiry: number = 0;

async function getToken(): Promise<string> {
  if (shiprocketToken && Date.now() < tokenExpiry) {
    return shiprocketToken;
  }

  const response = await fetch(`${SHIPROCKET_BASE_URL}/auth/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      email: process.env.SHIPROCKET_EMAIL,
      password: process.env.SHIPROCKET_PASSWORD,
    }),
  });

  const data = await response.json();
  shiprocketToken = data.token;
  tokenExpiry = Date.now() + 9 * 24 * 60 * 60 * 1000; // 9 days
  return shiprocketToken!;
}

async function shiprocketFetch(endpoint: string, options: RequestInit = {}) {
  const token = await getToken();
  const response = await fetch(`${SHIPROCKET_BASE_URL}${endpoint}`, {
    ...options,
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
      ...options.headers,
    },
  });
  return response.json();
}

export async function createShiprocketOrder(orderData: {
  orderNumber: string;
  orderDate: string;
  customerName: string;
  customerEmail: string;
  customerPhone: string;
  address: string;
  address2?: string;
  city: string;
  state: string;
  pincode: string;
  items: {
    name: string;
    sku: string;
    quantity: number;
    sellingPrice: number;
    weight: number;
  }[];
  subtotal: number;
  paymentMethod: string;
  weight: number;
}) {
  return shiprocketFetch("/orders/create/adhoc", {
    method: "POST",
    body: JSON.stringify({
      order_id: orderData.orderNumber,
      order_date: orderData.orderDate,
      pickup_location: "Primary",
      billing_customer_name: orderData.customerName,
      billing_last_name: "",
      billing_address: orderData.address,
      billing_address_2: orderData.address2 || "",
      billing_city: orderData.city,
      billing_pincode: orderData.pincode,
      billing_state: orderData.state,
      billing_country: "India",
      billing_email: orderData.customerEmail,
      billing_phone: orderData.customerPhone,
      shipping_is_billing: true,
      order_items: orderData.items.map((item) => ({
        name: item.name,
        sku: item.sku || "DEFAULT",
        units: item.quantity,
        selling_price: item.sellingPrice,
        discount: 0,
        tax: 0,
        hsn: "",
      })),
      payment_method: orderData.paymentMethod === "COD" ? "COD" : "Prepaid",
      sub_total: orderData.subtotal,
      length: 20,
      breadth: 15,
      height: 10,
      weight: orderData.weight / 1000, // Convert grams to kg
    }),
  });
}

export async function generateAWB(shipmentId: string, courierId?: number) {
  return shiprocketFetch("/courier/assign/awb", {
    method: "POST",
    body: JSON.stringify({
      shipment_id: shipmentId,
      ...(courierId && { courier_id: courierId }),
    }),
  });
}

export async function trackShipment(awbCode: string) {
  return shiprocketFetch(`/courier/track/awb/${awbCode}`);
}

export async function cancelShiprocketOrder(orderId: string) {
  return shiprocketFetch("/orders/cancel", {
    method: "POST",
    body: JSON.stringify({ ids: [orderId] }),
  });
}

export async function checkServiceability(pincode: string, weight: number) {
  return shiprocketFetch(
    `/courier/serviceability/?pickup_postcode=${process.env.SHIPROCKET_PICKUP_PINCODE}&delivery_postcode=${pincode}&weight=${weight / 1000}&cod=1`
  );
}

export async function getShiprocketCouriers() {
  return shiprocketFetch("/courier/courierListWithCounts");
}
