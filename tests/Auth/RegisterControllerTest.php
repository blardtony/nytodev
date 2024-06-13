<?php

declare(strict_types=1);

namespace App\Tests\Auth;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    private const string REGISTER_PATH = '/inscription';
    private const string REGISTER_BUTTON_LABEL = "S'inscrire";
    public function testRegisterSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', self::REGISTER_PATH);
        $form = $crawler->selectButton(self::REGISTER_BUTTON_LABEL)->form([
            'registration_form' => [
                'email' => 'jane@doe.fr',
                'plainPassword' => [
                    'first' => 'password',
                    'second' => 'password',
                ],
            ],
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertEquals(1, $client->getCrawler()->filter('.alert-success')->count());
    }
}
