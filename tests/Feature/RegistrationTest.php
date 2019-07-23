<?php
namespace Tests\Feature;
use App\Auth;
use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Mail\ConfirmYourEmail;
use Illuminate\Auth\Events\Registered;

class RegistrationTest extends TestCase
{
   
    
   use DatabaseMigrations;
    
   /** @test */
   public function confirmation_email_is_sent_upon_registration()
   { 
     Mail::fake();
     event( new Registered($user = factory('App\User')->create() ));
     Mail::assertSent(ConfirmYourEmail::class);
  
   }
   
   /** @test */
   public function user_can_confirm_email_address()
   { 

            $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);
        $user = User::whereName('John')->first();
        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);
        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('questions'));
        $this->assertTrue($user->fresh()->confirmed);
}

}
