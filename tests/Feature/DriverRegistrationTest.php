<?php

namespace Tests\Feature;

use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DriverRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_driver_registration_page_is_accessible(): void
    {
        $response = $this->get('/driver/register');

        $response->assertStatus(200)
            ->assertSee('Apply to get a FREE Mobiplay device')
            ->assertSee('First Name')
            ->assertSee('Last Name')
            ->assertSee('Phone Number')
            ->assertSee('Vehicle Type');
    }

    public function test_driver_can_register_with_valid_data(): void
    {
        Storage::fake('public');

        $driverData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'country' => 'United States',
            'state' => 'California',
            'city' => 'Los Angeles',
            'postal_code' => '90210',
            'license_number' => 'DL123456789',
            'car_make' => 'Toyota',
            'car_model' => 'Camry',
            'car_year' => '2020',
            'trips_per_month' => 50,
            'vehicle_type' => 'sedan',
            'vehicle_number' => 'ABC123',
        ];

        $response = $this->post('/driver/register', $driverData);

        $response->assertRedirect('/driver');

        $this->assertDatabaseHas('drivers', [
            'email' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'verification_status' => 'verified',
        ]);
    }

    /**
     * @skip This test is skipped because document upload was removed for quick onboarding
     */
    public function test_driver_can_upload_documents(): void
    {
        $this->markTestSkipped('Document upload functionality removed for quick onboarding');
        Storage::fake('public');

        // Create a driver first
        $driver = Driver::factory()->create([
            'verification_status' => 'pending',
        ]);

        // Create fake files
        $uberScreenshot = UploadedFile::fake()->image('uber_screenshot.jpg');
        $identityDocument = UploadedFile::fake()->image('identity_document.jpg');
        $vehicleNumberPlate = UploadedFile::fake()->image('vehicle_plate.jpg');

        $response = $this->withSession(['driver_id' => $driver->id])
            ->post('/driver/documents', [
                'uber_screenshot' => $uberScreenshot,
                'identity_document' => $identityDocument,
                'vehicle_number_plate' => $vehicleNumberPlate,
            ]);

        $response->assertRedirect('/driver/verification-status');

        // Check files were stored (files are stored in subdirectories by driver ID)
        $driverDir = 'driver-documents/'.$driver->id;

        // The files should be stored in subdirectories
        $storedFiles = Storage::disk('public')->allFiles($driverDir);
        $this->assertCount(3, $storedFiles); // Should have 3 files stored

        // Verify the files exist in their respective subdirectories
        $this->assertTrue(count(Storage::disk('public')->files($driverDir.'/uber')) > 0);
        $this->assertTrue(count(Storage::disk('public')->files($driverDir.'/identity')) > 0);
        $this->assertTrue(count(Storage::disk('public')->files($driverDir.'/vehicle')) > 0);

        // Check database was updated
        $driver->refresh();
        $this->assertNotNull($driver->uber_screenshot);
        $this->assertNotNull($driver->identity_document);
        $this->assertNotNull($driver->vehicle_number_plate);
        $this->assertEquals('under_review', $driver->verification_status);
    }

    /**
     * @skip This test is skipped because verification status page was removed for quick onboarding
     */
    public function test_driver_can_view_verification_status(): void
    {
        $this->markTestSkipped('Verification status page removed for quick onboarding');
    }

    public function test_driver_registration_requires_valid_email(): void
    {
        $response = $this->post('/driver/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
            'phone' => '+1234567890',
            'license_number' => 'DL123456789',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_driver_registration_requires_unique_email(): void
    {
        Driver::factory()->create(['email' => 'john@example.com']);

        $response = $this->post('/driver/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'license_number' => 'DL123456789',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
