<?php

use App\Models\User;
use App\Models\Contact;
use App\Events\NewRegisteredUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewRegisteredUserUnitTest extends TestCase
{
    use DatabaseTransactions;

   /**
     * @covers App\Events\NewRegisteredUser::__construct
     * @covers App\Handlers\Events\LinkUserToExistingContacts::handle
     * @test
     */
    public function it_fires_NewRegisteredUser_event_and_links_user()
    {
        $contact = $this->createContact(['email' => 'guest@example.org']);

        $user = $this->createUser(['email' => 'guest@example.org', 'password' => bcrypt('demoguest')]);

        event(new NewRegisteredUser($user));

        $this->seeInDatabase('contacts', ['email' => $user->email, 'user_id' => $user->id]);
    }

    /////////////
    // HELPERS //
    /////////////

    private function createUser($overrides = [])
    {
        $user = factory(User::class)->create($overrides);

        return $user;
    }

    private function createContact($overrides = [])
    {
        $contact = factory(Contact::class)->create($overrides);

        return $contact;
    }
}