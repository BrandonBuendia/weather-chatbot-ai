<?php

namespace Tests\Unit;

use App\DTOs\WeatherData;
use App\Services\OpenAIService;
use App\Services\OpenMeteoService;
use Illuminate\Support\Facades\Log;
use Mockery;
use OpenAI\Laravel\Facades\OpenAI as OpenAIFacade;
use OpenAI\Responses\Chat\CreateResponse;
use Tests\TestCase;

class OpenAIServiceTest extends TestCase
{
    protected OpenAIService $service;
    protected $mockWeatherService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockWeatherService = Mockery::mock(OpenMeteoService::class);
        $this->service = new OpenAIService($this->mockWeatherService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_chat_returns_assistant_response(): void
    {
        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->never();

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => '¡Hola! Soy MeteoBot, tu asistente meteorológico.',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Hola'],
        ];

        $response = $this->service->chat($messages, 1);

        $this->assertIsString($response);
        $this->assertEquals('¡Hola! Soy MeteoBot, tu asistente meteorológico.', $response);
    }

    public function test_chat_includes_system_prompt(): void
    {
        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->never();

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Respuesta',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages, 1);

        $this->assertIsString($response);
    }

    public function test_chat_uses_correct_model_and_parameters(): void
    {
        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->never();

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Respuesta',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages);

        $this->assertIsString($response);
    }

    public function test_chat_detects_weather_keywords_and_fetches_data(): void
    {
        $weatherData = new WeatherData(
            temperature: 22.5,
            weatherCode: 0,
            latitude: 40.4168,
            longitude: -3.7038,
            timezone: 'Europe/Madrid'
        );

        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->with('madrid')
            ->once()
            ->andReturn($weatherData);

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => '¿Quieres saber el clima de Madrid?',
                        ],
                    ],
                ],
            ]),
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'El clima en Madrid es soleado con 22.5°C',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => '¿Qué tiempo hace en Madrid?'],
        ];

        $response = $this->service->chat($messages);

        $this->assertStringContainsString('22.5', $response);
    }

    public function test_chat_extracts_multiple_city_names(): void
    {
        $weatherData = new WeatherData(
            temperature: 18.0,
            weatherCode: 61,
            latitude: 41.3851,
            longitude: 2.1734,
            timezone: 'Europe/Madrid'
        );

        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->with('barcelona')
            ->once()
            ->andReturn($weatherData);

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Clima en Barcelona',
                        ],
                    ],
                ],
            ]),
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Lluvia en Barcelona',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Clima en Barcelona'],
        ];

        $this->service->chat($messages);
    }

    public function test_chat_handles_rate_limit_error(): void
    {
        Log::shouldReceive('error')->once();

        OpenAIFacade::shouldReceive('chat->create')
            ->andThrow(new \Exception('Rate limit exceeded'));

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages);

        $this->assertStringContainsString('límite de peticiones', $response);
    }

    public function test_chat_handles_authentication_error(): void
    {
        Log::shouldReceive('error')->once();

        OpenAIFacade::shouldReceive('chat->create')
            ->andThrow(new \Exception('Incorrect API key provided'));

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages);

        $this->assertStringContainsString('autenticación', $response);
    }

    public function test_chat_handles_timeout_error(): void
    {
        Log::shouldReceive('error')->once();

        OpenAIFacade::shouldReceive('chat->create')
            ->andThrow(new \Exception('Request timed out'));

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages);

        $this->assertStringContainsString('demasiado tiempo', $response);
    }

    public function test_chat_handles_network_error(): void
    {
        Log::shouldReceive('error')->once();

        OpenAIFacade::shouldReceive('chat->create')
            ->andThrow(new \Exception('Could not resolve host'));

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages);

        $this->assertStringContainsString('conexión', $response);
    }

    public function test_chat_handles_generic_error(): void
    {
        Log::shouldReceive('error')->once();

        OpenAIFacade::shouldReceive('chat->create')
            ->andThrow(new \Exception('Unknown error'));

        $messages = [
            ['role' => 'user', 'content' => 'Test'],
        ];

        $response = $this->service->chat($messages);

        $this->assertStringContainsString('ocurrió un error', $response);
    }

    public function test_chat_formats_messages_correctly(): void
    {
        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->never();

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Respuesta',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Mensaje 1'],
            ['role' => 'assistant', 'content' => 'Respuesta 1'],
            ['role' => 'user', 'content' => 'Mensaje 2'],
        ];

        $response = $this->service->chat($messages);

        $this->assertIsString($response);
    }

    public function test_chat_detects_weather_keywords(): void
    {
        $keywords = ['clima', 'tiempo', 'temperatura'];

        foreach ($keywords as $keyword) {
            $weatherData = new WeatherData(
                temperature: 20.0,
                weatherCode: 0,
                latitude: 40.4168,
                longitude: -3.7038,
                timezone: 'Europe/Madrid'
            );

            $this->mockWeatherService
                ->shouldReceive('getWeatherByCity')
                ->with('madrid')
                ->once()
                ->andReturn($weatherData);

            OpenAIFacade::fake([
                CreateResponse::fake([
                    'choices' => [
                        [
                            'message' => [
                                'content' => "Hablando sobre {$keyword} en Madrid",
                            ],
                        ],
                    ],
                ]),
                CreateResponse::fake([
                    'choices' => [
                        [
                            'message' => [
                                'content' => 'Respuesta enriquecida',
                            ],
                        ],
                    ],
                ]),
            ]);

            $messages = [
                ['role' => 'user', 'content' => "¿Cómo está el {$keyword} en Madrid?"],
            ];

            $response = $this->service->chat($messages);

            $this->assertIsString($response);
        }
    }

    public function test_chat_does_not_fetch_weather_without_city(): void
    {
        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->never();

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Respuesta sin ciudad',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => '¿Cómo está el clima?'],
        ];

        $response = $this->service->chat($messages);

        $this->assertEquals('Respuesta sin ciudad', $response);
    }

    public function test_chat_handles_null_weather_data(): void
    {
        $this->mockWeatherService
            ->shouldReceive('getWeatherByCity')
            ->with('madrid')
            ->once()
            ->andReturn(null);

        OpenAIFacade::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Hablando de clima en Madrid',
                        ],
                    ],
                ],
            ]),
        ]);

        $messages = [
            ['role' => 'user', 'content' => 'Clima en Madrid'],
        ];

        $response = $this->service->chat($messages);

        $this->assertEquals('Hablando de clima en Madrid', $response);
    }

    public function test_chat_recognizes_common_cities(): void
    {
        $cities = ['madrid', 'barcelona', 'paris'];

        foreach ($cities as $city) {
            $weatherData = new WeatherData(
                temperature: 20.0,
                weatherCode: 0,
                latitude: 40.0,
                longitude: -3.0,
                timezone: 'Europe/Madrid'
            );

            $this->mockWeatherService
                ->shouldReceive('getWeatherByCity')
                ->with($city)
                ->once()
                ->andReturn($weatherData);

            OpenAIFacade::fake([
                CreateResponse::fake([
                    'choices' => [
                        [
                            'message' => [
                                'content' => "Clima en {$city}",
                            ],
                        ],
                    ],
                ]),
                CreateResponse::fake([
                    'choices' => [
                        [
                            'message' => [
                                'content' => 'Respuesta enriquecida',
                            ],
                        ],
                    ],
                ]),
            ]);

            $messages = [
                ['role' => 'user', 'content' => "Clima en {$city}"],
            ];

            $response = $this->service->chat($messages);

            $this->assertIsString($response);
        }
    }
}

# cGFuZ29saW4=
