<?php

namespace Tests\Feature;

//Models
use App\Models\{
    Expense,
    User
};

//Requests
use Illuminate\Http\Request;

//Resources
use App\Http\Resources\ExpenseResource;

//Miscellaneous
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use DatabaseTransactions;

    public function test_correctly_api_resource(): void
    {
        $keys         = ['id', 'date', 'description', 'value', 'user_id'];

        $request      = new Request();
        $expense      = Expense::factory()->create();

        $apiResource = (new ExpenseResource($expense))->toArray($request);

        $this->assertIsArray($apiResource);

        foreach ($keys as $key){
            $this->assertArrayHasKey($key, $apiResource);
        }
    }

    public function test_list_all_expenses(): void
    {
        $count = 10;

        $this->authUser();

        Expense::factory($count)->create();

        $response = $this->get('/api/expenses');

        $content  = $this->makeContentResponse(response: $response);

        $message  = $content->get('message');
        $data     = $content->get('data');

        $this->assertGreaterThanOrEqual($count, count($data));
        $this->assertIsString($message);

        $response->assertStatus(200);
    }

    public function test_create_expense(): void
    {
        $user = User::factory()->create();
        $this->authUser(user: $user);

        $userId = $user->getAttribute('id');

        $response = $this->post('/api/expenses', [
            'description' => 'store test',
            'value'       => '125,00',
            'date'        => '24/01/2024',
            'user_id'     => $userId
        ]);

        $content = $this->makeContentResponse(response: $response);

        $message = $content->get('message');
        $data    = $content->get('data');

        $this->assertIsString($message);

        $this->assertNotEmpty($data);
        $this->assertIsInt((int) $data['id']);
        $this->assertEquals($userId, $data['user_id']);


        $response->assertStatus(201);
    }

    public function test_show_allowed_expense(): void
    {
        $user   = User::factory()->create();
        $userId = $user->getAttribute('id');

        $this->authUser(user: $user);

        $expense   = Expense::factory()->create([
            'user_id' => $userId
        ]);

        $expenseId = $expense->getAttribute('id');

        $response  = $this->get("/api/expenses/$expenseId");

        $content   = $this->makeContentResponse(response: $response);
        $data      = $content->get('data');

        $this->assertEquals($expenseId, (int) $data['id']);
        $response->assertStatus(200);
    }

    public function test_show_not_allowed_expense(): void
    {
        $this->authUser();

        $expense   = Expense::factory()->create();
        $expenseId = $expense->getAttribute('id');

        $response  = $this->get("/api/expenses/$expenseId");
        $response->assertStatus(401);
    }

    public function test_update_allowed_expense(): void
    {
        $description = 'Final description';

        $user   = User::factory()->create();
        $userId = $user->getAttribute('id');

        $this->authUser(user: $user);

        $expense   = Expense::factory()->create([
            'description' => 'initial description',
            'user_id'     => $userId
        ]);
        $expenseId = $expense->getAttribute('id');

        $response  = $this->put("/api/expenses/$expenseId", [
            'description' => $description
        ]);

        $content   = $this->makeContentResponse(response: $response);
        $data      = $content->get('data');

        $this->assertEquals($description, $data['description']);
        $response->assertStatus(200);
    }

    public function test_update_not_allowed_expense(): void
    {
        $this->authUser();

        $expense   = Expense::factory()->create([
            'description' => 'initial description'
        ]);

        $expenseId = $expense->getAttribute('id');

        $response  = $this->put("/api/expenses/$expenseId", [
            'description' => 'Not allowed expense'
        ]);

        $response->assertStatus(401);
    }

    public function test_delete_allowed_expense(): void
    {
        $user      = User::factory()->create();
        $userId    = $user->getAttribute('id');

        $this->authUser(user: $user);

        $expense    = Expense::factory()->create([
            'user_id' => $userId
        ]);

        $expenseId  = $expense->getAttribute('id');

        $response   = $this->delete("/api/expenses/$expenseId");

        $response->assertStatus(200);
    }

    public function test_delete_not_allowed_expense(): void
    {
        $this->authUser();

        $expense    = Expense::factory()->create();
        $expenseId  = $expense->getAttribute('id');

        $response   = $this->delete("/api/expenses/$expenseId");

        $response->assertStatus(401);
    }

    private function authUser(User $user = null): void
    {
        if(!$user){
            $user = User::factory()->create();
        }

        Sanctum::actingAs($user);
    }
    private function makeContentResponse(TestResponse $response): Collection
    {
        return collect(json_decode($response->content(), true));
    }
}
