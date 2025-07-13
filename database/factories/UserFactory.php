<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\Users\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $prefixes = [
            '93', // Syriatel
            '94', // MTN
            '95',
            '96',
            '98',
            '99',
        ];

        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone'=> fake()->unique()->numerify('+963' . fake()->randomElement($prefixes) . '#######'),
            'address'=>fake()->address(),
            'gender'=>fake()->randomElement(Gender::cases())->value,
            'user_type'=>fake()->randomElement(UserType::cases())->value,
            'is_active'=>1,
            'birthday'=>fake()->date(),
            'age'=>fake()->numberBetween(18, 100),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }



    public function admin(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Admin->value,
        ]);
    }

    public function doctor(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Doctor->value,
        ]);
    }

    public function nurse(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Nurse->value,
        ]);
    }

    public function reception(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Reception->value,
        ]);
    }

    public function secretary(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Secretary->value,
        ]);
    }

    public function patient(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Patient->value,
        ]);
    }

    public function driver(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Driver->value,
        ]);
    }

}
