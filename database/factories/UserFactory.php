<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail;

        return [
            'name' => $this->faker->name,
            'username' => $email,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => 'content'
        ];
    }

    /**
     * Developer
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function developer()
    {
        return $this->state([
            'role' => 'developer'
        ]);
    }

    /**
     * Super Admin
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function superAdmin()
    {
        return $this->state([
            'role' => 'superAdmin'
        ]);
    }

    /**
     * Admin
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state([
            'role' => 'admin'
        ]);
    }

    /**
     * Booking agent
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function booking()
    {
        return $this->state([
            'role' => 'booking'
        ]);
    }

    /**
     * Content User
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function content()
    {
        return $this->state([
            'role' => 'content'
        ]);
    }

    /**
     * Affiliate User
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function affiliate()
    {
        return $this->state([
            'role' => 'affiliate'
        ]);
    }
}
