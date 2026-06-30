"use client";

import React from "react";
import { MapPin, Phone, Mail, Clock, Send } from "lucide-react";
import Button from "@/components/ui/Button";
import Input from "@/components/ui/Input";
import { SITE_CONFIG } from "@/lib/constants";

export default function ContactPage() {
  return (
    <div className="max-w-7xl mx-auto px-4 py-12">
      <div className="text-center mb-12">
        <h1 className="text-3xl font-bold text-primary-dark">Contact Us</h1>
        <p className="text-gray-500 mt-2">We&apos;d love to hear from you</p>
      </div>

      <div className="grid lg:grid-cols-2 gap-12">
        {/* Contact Form */}
        <div className="bg-white p-8 rounded-2xl border border-border">
          <h2 className="text-xl font-bold text-gray-900 mb-6">Send us a message</h2>
          <form className="space-y-4">
            <div className="grid md:grid-cols-2 gap-4">
              <Input label="Full Name" placeholder="Your name" />
              <Input label="Phone" placeholder="+91 XXXXX XXXXX" />
            </div>
            <Input label="Email" type="email" placeholder="your@email.com" />
            <Input label="Subject" placeholder="How can we help?" />
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
              <textarea
                rows={5}
                placeholder="Your message..."
                className="w-full px-4 py-3 rounded-xl border border-border bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 text-sm"
              />
            </div>
            <Button variant="cta" fullWidth>
              <Send size={16} className="mr-2" /> Send Message
            </Button>
          </form>
        </div>

        {/* Contact Info */}
        <div className="space-y-6">
          <div className="bg-accent/50 p-6 rounded-2xl border border-primary/10">
            <h2 className="text-xl font-bold text-gray-900 mb-6">Get in Touch</h2>
            <div className="space-y-4">
              <div className="flex items-start gap-4">
                <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                  <MapPin size={20} className="text-primary" />
                </div>
                <div>
                  <h3 className="font-medium text-gray-800">Address</h3>
                  <p className="text-sm text-gray-500">
                    {SITE_CONFIG.address.line1}, {SITE_CONFIG.address.line2},
                    {SITE_CONFIG.address.city}, {SITE_CONFIG.address.state} - {SITE_CONFIG.address.pincode}
                  </p>
                </div>
              </div>
              <div className="flex items-start gap-4">
                <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                  <Phone size={20} className="text-primary" />
                </div>
                <div>
                  <h3 className="font-medium text-gray-800">Phone</h3>
                  <p className="text-sm text-gray-500">{SITE_CONFIG.phone}</p>
                </div>
              </div>
              <div className="flex items-start gap-4">
                <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                  <Mail size={20} className="text-primary" />
                </div>
                <div>
                  <h3 className="font-medium text-gray-800">Email</h3>
                  <p className="text-sm text-gray-500">{SITE_CONFIG.email}</p>
                </div>
              </div>
              <div className="flex items-start gap-4">
                <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                  <Clock size={20} className="text-primary" />
                </div>
                <div>
                  <h3 className="font-medium text-gray-800">Working Hours</h3>
                  <p className="text-sm text-gray-500">24/7 Support Available</p>
                </div>
              </div>
            </div>
          </div>

          {/* WhatsApp CTA */}
          <a
            href={`https://wa.me/${SITE_CONFIG.whatsapp}?text=Hi, I have a question about Shivara products`}
            target="_blank"
            rel="noopener noreferrer"
            className="block bg-green-600 text-white p-6 rounded-2xl hover:bg-green-700 transition text-center"
          >
            <p className="text-lg font-bold">Chat on WhatsApp</p>
            <p className="text-sm text-green-100 mt-1">Get instant replies from our team</p>
          </a>
        </div>
      </div>
    </div>
  );
}
