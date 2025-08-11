<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DebitCard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DebitCardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Passport::actingAs($this->user);
    }

    public function testCustomerCanSeeAListOfDebitCards()
    {
        // get /debit-cards
        // Bersihkan tabel debit_cards
        \App\Models\DebitCard::truncate();

        // Buat 3 debit card aktif milik user yang sedang login
        \App\Models\DebitCard::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'disabled_at' => null,
            'number' => fn() => rand(1_000_000_000, 2_147_483_647), // nomor aman sesuai INT
        ]);

        // Request GET ke /api/debit-cards
        $response = $this->getJson('/api/debit-cards');

        // Ambil data dari response JSON
        $json = $response->json();
        $cards = $json['data'] ?? $json;

        // Pastikan status HTTP 200 OK
        $response->assertStatus(200);

        // Pastikan ada 3 data debit card
        $this->assertCount(3, $cards);

        // Pastikan setiap item punya field yang dibutuhkan
        foreach ($cards as $card) {
            $this->assertArrayHasKey('id', $card);
            $this->assertArrayHasKey('number', $card);
            $this->assertArrayHasKey('type', $card);
            $this->assertArrayHasKey('expiration_date', $card);
            $this->assertArrayHasKey('is_active', $card);
        }
    }

    public function testCustomerCannotSeeAListOfDebitCardsOfOtherCustomers()
    {
        // get /debit-cards
    }

    public function testCustomerCanCreateADebitCard()
    {
        // post /debit-cards
    }

    public function testCustomerCanSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}
    }

    public function testCustomerCannotSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}
    }

    public function testCustomerCanActivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
    }

    public function testCustomerCanDeactivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
    }

    public function testCustomerCannotUpdateADebitCardWithWrongValidation()
    {
        // put api/debit-cards/{debitCard}
    }

    public function testCustomerCanDeleteADebitCard()
    {
        // delete api/debit-cards/{debitCard}
    }

    public function testCustomerCannotDeleteADebitCardWithTransaction()
    {
        // delete api/debit-cards/{debitCard}
    }

    // Extra bonus for extra tests :)
}
