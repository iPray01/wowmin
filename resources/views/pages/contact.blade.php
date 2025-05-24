@extends('layouts.app')

@section('header')
Contact Us
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg p-6">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <i class="fas fa-envelope text-4xl text-crimson mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900">Get in Touch</h2>
            <p class="mt-4 text-gray-600">
                We'd love to hear from you. Please use the form below to send us a message.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-crimson mt-1"></i>
                            <span class="text-gray-600">123 Church Street, City, State 12345</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-crimson"></i>
                            <span class="text-gray-600">(555) 123-4567</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-crimson"></i>
                            <span class="text-gray-600">info@wowministry.org</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Service Times</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>Sunday Service: 9:00 AM & 11:00 AM</li>
                        <li>Wednesday Bible Study: 7:00 PM</li>
                        <li>Youth Service: Friday 6:00 PM</li>
                    </ul>
                </div>
            </div>

            <div>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-crimson focus:ring-crimson">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-crimson focus:ring-crimson">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-crimson focus:ring-crimson">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" id="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-crimson focus:ring-crimson"></textarea>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-gradient-to-r from-[#DC143C] to-[#8B0000] text-white py-2 px-4 rounded-md hover:opacity-90 transition-opacity duration-200">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
