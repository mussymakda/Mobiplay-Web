<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverRegistrationController extends Controller
{
    /**
     * Show driver registration form
     */
    public function showRegistrationForm()
    {
        return view('driver.register');
    }

    /**
     * Handle driver registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'car_make' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'car_year' => 'required|integer|min:1990|max:'.(date('Y') + 1),
            'trips_per_month' => 'required|integer|min:1',
            'vehicle_type' => 'required|in:sedan,suv,hatchback,minivan',
            'license_number' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate unique IDs for license and vehicle if they are placeholders
        $licenseNumber = $request->license_number;
        $vehicleNumber = $request->vehicle_number;

        // If they're still placeholder values, generate unique ones
        if (in_array($licenseNumber, ['TBD', 'LIC-', ''])) {
            $licenseNumber = 'LIC-'.uniqid();
        }

        if (in_array($vehicleNumber, ['TBD', 'TEMP-', ''])) {
            $vehicleNumber = 'TEMP-'.uniqid();
        }

        // Create driver record for GPS tracking system
        $driver = Driver::create([
            'name' => $request->first_name.' '.$request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'license_number' => $request->license_number,
            'device_id' => 'DEVICE_'.strtoupper(uniqid()), // Generate unique device ID
            'vehicle_number' => $vehicleNumber ?? 'VEHICLE_'.strtoupper(substr(uniqid(), -6)),
            'vehicle_number_plate' => $request->vehicle_number_plate,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'verification_status' => 'pending',
        ]);

        // Check if this is an AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Welcome to Mobiplay! Your driver account has been created successfully.',
                'driver_id' => $driver->id,
            ]);
        }

        // Regular form submission - redirect with success message
        return redirect()->route('driver')
            ->with('success', 'Welcome to Mobiplay! Your driver account has been created successfully.');
    }

    /**
     * Show document upload form
     */
    public function showDocumentForm()
    {
        $driverId = session('driver_id');

        if (! $driverId) {
            return redirect()->route('driver.register')
                ->withErrors(['error' => 'Please complete registration first.']);
        }

        $driver = Driver::findOrFail($driverId);

        return view('driver.documents', compact('driver'));
    }

    /**
     * Handle document uploads
     */
    public function uploadDocuments(Request $request)
    {
        $driverId = session('driver_id');

        if (! $driverId) {
            return redirect()->route('driver.register')
                ->withErrors(['error' => 'Please complete registration first.']);
        }

        $driver = Driver::findOrFail($driverId);

        $validator = Validator::make($request->all(), [
            'uber_screenshot' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB
            'identity_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'vehicle_number_plate' => 'required|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            // Create directory for driver documents
            $driverDir = 'driver-documents/'.$driver->id;

            // Upload Uber screenshot
            if ($request->hasFile('uber_screenshot')) {
                $uberFile = $request->file('uber_screenshot');
                $uberPath = $uberFile->store($driverDir.'/uber', 'public');
                $driver->uber_screenshot = $uberPath;
            }

            // Upload identity document
            if ($request->hasFile('identity_document')) {
                $identityFile = $request->file('identity_document');
                $identityPath = $identityFile->store($driverDir.'/identity', 'public');
                $driver->identity_document = $identityPath;
            }

            // Upload vehicle number plate
            if ($request->hasFile('vehicle_number_plate')) {
                $plateFile = $request->file('vehicle_number_plate');
                $platePath = $plateFile->store($driverDir.'/vehicle', 'public');
                $driver->vehicle_number_plate = $platePath;
            }

            // Mark documents as uploaded and change status to under review
            $driver->markDocumentsUploaded();
            $driver->save();

            return redirect()->route('driver.verification-status')
                ->with('success', 'Documents uploaded successfully! Your application is now under review.');

        } catch (\Exception $e) {
            return back()->withErrors(['upload' => 'Failed to upload documents. Please try again.']);
        }
    }

    /**
     * Show verification status page
     */
    public function showVerificationStatus()
    {
        $driverId = session('driver_id');

        if (! $driverId) {
            return redirect()->route('driver.register')
                ->withErrors(['error' => 'Please complete registration first.']);
        }

        $driver = Driver::findOrFail($driverId);

        return view('driver.verification-status', compact('driver'));
    }

    /**
     * Check verification status (AJAX endpoint)
     */
    public function checkVerificationStatus(Driver $driver)
    {
        return response()->json([
            'status' => $driver->verification_status,
            'verified_at' => $driver->verified_at,
            'rejection_reason' => $driver->rejection_reason,
            'badge' => $driver->getVerificationStatusBadge(),
        ]);
    }

    /**
     * Reset driver status (for GPS tracking system)
     */
    public function resetDriver(Driver $driver)
    {
        // Reset driver location for tablet reconnection
        $driver->update([
            'is_active' => false,
            'current_latitude' => null,
            'current_longitude' => null,
            'last_location_update' => null,
        ]);

        return redirect()->route('admin')
            ->with('info', 'Driver status has been reset. Awaiting GPS reconnection.');
    }
}
