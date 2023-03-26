<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\V1\AttacksController;
use App\Http\Requests\Attacks\AttackStoreRequest;
use App\Services\AttacksService;
use App\Models\Attack;
use Illuminate\Http\Response;
use Mockery\MockInterface;
use Tests\TestCase;



class AttackControllerTest extends TestCase
{
    /**
     * @var AttackController
     */
    private $controller;

    /**
     * @var AttackService|MockInterface
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->mock(AttacksService::class);
        $this->controller = new AttacksController($this->service);
    }

    public function test_successful_attack()
    {
        $attacker_id = 1;
        $defender_id = 2;
        $attack_type_id = 3;

        $request = new AttackStoreRequest([
            'attack_type_id' => $attack_type_id,
            'attacker_id' => $attacker_id,
            'defender_id' => $defender_id,
        ]);

        $attacker_data = [
            'life' => 100,
            'points' => 50,
        ];

        $defender_data = [
            'life' => 100,
            'points' => 30,
        ];

        $latest_attack = ['id' => 1];

        $this->service->shouldReceive('generatePoints')
            ->with($attacker_id)
            ->andReturn($attacker_data);

        $this->service->shouldReceive('generatePoints')
            ->with($defender_id)
            ->andReturn($defender_data);

        $this->service->shouldReceive('latest_attack')
            ->with($attacker_id)
            ->andReturn($latest_attack);

        $this->service->shouldReceive('attackCreate')
            ->with($attack_type_id, $latest_attack, $attacker_data, $defender_data, $defender_id, $request)
            ->andReturn(new Response('', 201));

        $response = $this->controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testIndex()
    {
        // Crear algunos ataques para probar
        $attacks =  Attack::factory(3)->create();

        // Hacer una solicitud GET a la ruta index del controlador
        $response = $this->get('api/v1/attacks');

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Decodificar el contenido JSON de la respuesta
        $responseData = $response->decodeResponseJson();

        // Asegurarse de que la respuesta tenga éxito
        $this->assertTrue($responseData['success']);

    }

    public function test_defender_already_dead()
    {
        // Test scenario where the defender is already dead
    }

    public function test_attacker_already_dead()
    {
        // Test scenario where the attacker is already dead
    }

    public function test_attacking_yourself()
    {
        // Test scenario where the attacker is attacking themselves
    }
}
