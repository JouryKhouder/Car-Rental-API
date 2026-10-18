<?php

namespace Tests\Feature;

use App\Models\CarCategory;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\DriveType;
use App\Models\FuelType;
use App\Models\Make;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CarRentalIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function lookups(): array
    {
        $make = Make::create(['name' => 'Toyota']);
        $model = CarModel::create(['name' => 'Camry', 'make_id' => $make->id]);
        return [
            $model,
            CarCategory::create(['catygory_name' => 'Sedan']),
            FuelType::create(['fuel_name' => 'Petrol']),
            DriveType::create(['type_name' => 'FWD']),
        ];
    }

    private function customer(): User
    {
        $user = User::factory()->create(['role' => 'customer']);
        Customer::create([
            'user_id' => $user->id,
            'country' => 'Syria', 'city' => 'Damascus', 'address' => 'Main Street',
            'driver_license_number' => 'DL-'.$user->id,
            'national_id' => 'NID-'.$user->id,
        ]);
        return $user;
    }

    public function test_public_partner_registration_is_allowed(): void
    {
        Mail::fake();

        $this->postJson('/api/auth/register/partner', [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            // إضافة حقول وهمية عند تطوير المشروع
        ])->assertCreated();
    }

    public function test_lookup_endpoint_uses_api_names(): void
    {
        $this->lookups();
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);
        $this->getJson('/api/admin/lookups')->assertOk()
            ->assertJsonPath('data.categories.0.name', 'Sedan')
            ->assertJsonPath('data.fuel_types.0.name', 'Petrol')
            ->assertJsonPath('data.drive_types.0.name', 'FWD');
    }

    public function test_admin_can_create_vehicle_with_api_field_names(): void
    {
        [$model, $category, $fuel, $drive] = $this->lookups();
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        $response = $this->postJson('/api/admin/vehicles', [
            'model_id' => $model->id, 'car_category_id' => $category->id,
            'fuel_type_id' => $fuel->id, 'drive_type_id' => $drive->id,
            'year' => 2024, 'kilo_meterage' => 0, 'plate_number' => 'ABC-123', 'color' => 'Black',
            'seats' => 5, 'transmission' => 'automatic', 'rental_price_per_day' => 50,
        ])->assertCreated();
        $this->assertDatabaseHas('vehicles', ['model_id' => $model->id, 'rental_price_per_day' => 50]);
        $response->assertJsonPath('data.daily_rate', 50);
    }

    public function test_customer_can_list_available_vehicles(): void
    {
        [$model, $category, $fuel, $drive] = $this->lookups();
        Vehicle::create(['model_id' => $model->id, 'car_category_id' => $category->id, 'fuel_type_id' => $fuel->id, 'drive_type_id' => $drive->id, 'year' => 2024, 'kilo_meterage' => 100, 'plate_number' => 'ABC-123', 'status' => 'available', 'rental_price_per_day' => 50, 'color' => 'Black']);
        Sanctum::actingAs($this->customer());
        $this->getJson('/api/customer/vehicles')->assertOk()->assertJsonPath('data.data.0.plate_number', 'ABC-123');
    }

    public function test_customer_can_book_and_overlapping_booking_is_rejected(): void
    {
        [$model, $category, $fuel, $drive] = $this->lookups();
        $vehicle = Vehicle::create(['model_id' => $model->id, 'car_category_id' => $category->id, 'fuel_type_id' => $fuel->id, 'drive_type_id' => $drive->id, 'year' => 2024, 'kilo_meterage' => 100, 'plate_number' => 'ABC-123', 'status' => 'available', 'rental_price_per_day' => 50, 'color' => 'Black']);
        Mail::fake();
        Sanctum::actingAs($this->customer());
        $data = ['vehicle_id' => $vehicle->id, 'rental_start_date' => now()->addDay()->format('Y-m-d'), 'rental_end_date' => now()->addDays(4)->format('Y-m-d'), 'pickup_location' => 'A', 'dropoff_location' => 'B', 'paid_amount' => 100, 'payment_method' => 'cash'];
        $this->postJson('/api/customer/rentals', $data)->assertCreated()->assertJsonPath('data.total_price', 150);
        $this->postJson('/api/customer/rentals', $data)->assertStatus(409);
    }

    public function test_customer_update_returns_200_and_data(): void
    {
        $user = $this->customer();
        Sanctum::actingAs($user);
        $this->putJson('/api/customer/update', ['city' => 'Aleppo'])->assertOk()->assertJsonPath('data.city', 'Aleppo');
    }

    public function test_password_change_uses_old_password_field(): void
    {
        $user = $this->customer();
        $user->update(['password' => bcrypt('old-password')]);
        Sanctum::actingAs($user);
        $this->putJson('/api/auth/password', ['old_password' => 'old-password', 'new_password' => 'new-password', 'new_password_confirmation' => 'new-password'])->assertOk();
    }
}
