@extends('layouts.app')

@section('header')
Privacy Policy
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg p-6">
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="text-center mb-8">
            <i class="fas fa-shield-alt text-4xl text-crimson mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900">Privacy Policy</h2>
            <p class="mt-4 text-gray-600">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <div class="prose max-w-none">
            <section class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Information We Collect</h3>
                <p class="text-gray-600 mb-4">
                    We collect information that you provide directly to us, including:
                </p>
                <ul class="list-disc pl-5 text-gray-600 space-y-2">
                    <li>Personal information (name, email address, phone number)</li>
                    <li>Family information for membership records</li>
                    <li>Donation and financial transaction details</li>
                    <li>Prayer requests and communication preferences</li>
                    <li>Attendance records for church events and services</li>
                </ul>
            </section>

            <section class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">How We Use Your Information</h3>
                <p class="text-gray-600 mb-4">
                    We use the information we collect to:
                </p>
                <ul class="list-disc pl-5 text-gray-600 space-y-2">
                    <li>Manage church membership and communications</li>
                    <li>Process donations and maintain financial records</li>
                    <li>Coordinate ministry activities and events</li>
                    <li>Send important updates and announcements</li>
                    <li>Provide pastoral care and support</li>
                </ul>
            </section>

            <section class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Data Security</h3>
                <p class="text-gray-600">
                    We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. These measures include internal reviews of our data collection, storage, and processing practices and security measures, as well as physical security measures to guard against unauthorized access to systems where we store personal data.
                </p>
            </section>

            <section class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Your Rights</h3>
                <p class="text-gray-600 mb-4">
                    You have the right to:
                </p>
                <ul class="list-disc pl-5 text-gray-600 space-y-2">
                    <li>Access your personal information</li>
                    <li>Correct inaccurate or incomplete information</li>
                    <li>Request deletion of your information</li>
                    <li>Opt-out of communications</li>
                    <li>Express concerns about data handling</li>
                </ul>
            </section>

            <section>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Contact Us</h3>
                <p class="text-gray-600">
                    If you have any questions about this Privacy Policy or our data practices, please contact us at:
                </p>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">
                        WoW Ministry International<br>
                        Email: privacy@wowministry.org<br>
                        Phone: (555) 123-4567
                    </p>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
