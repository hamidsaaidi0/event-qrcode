<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    public function showForm()
    {
        $events = Event::all();
        return view('registration', compact('events'));
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        // Validate the form input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:registrations',
            'event_id' => 'required|exists:events,id',
        ]);

        // Generate unique QR code data
        $qrCodeData = uniqid('event-' . $request->event_id . '-', true);

        // Create the registration record
        $registration = Registration::create([
            'name' => $request->name,
            'email' => $request->email,
            'qr_code_data' => $qrCodeData,
            'event_id' => $request->event_id,
        ]);
        $event = Event::where('id', $request->event_id)->first();
        // Generate the QR code
        $qrCodePath = 'qrcodes/' . $registration->id . '.png';
        QrCode::format('png')->size(300)->generate($qrCodeData, public_path($qrCodePath));
        // Send email with the QR code attachment
        Mail::send('emails.qr-code', ['registration' => $registration, 'event' => $event], function ($message) use ($registration, $qrCodePath) {
            $message->to($registration->email)
                ->subject('Your Event QR Code')
                ->attach(public_path($qrCodePath));
        });

        // Return success message
        return back()->with('success', 'Registration successful! Check your email for the QR code.');
    }

    public function scanQRCode(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'qr_code_data' => 'required|string|exists:registrations,qr_code_data',
        ]);

        // Find the registration using the QR code data
        $registration = Registration::where('qr_code_data', $request->qr_code_data)->first();

        // Check if the QR code has already been used
        if ($registration->used) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been used.',
            ], 400);
        }

        // Mark the QR code as used
        $registration->used = true;
        $registration->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Access granted. QR code validated successfully.',
            'data' => $registration,
        ]);
    }}
