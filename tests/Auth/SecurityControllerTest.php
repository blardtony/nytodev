<?php

declare(strict_types=1);

namespace App\Tests\Auth;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private const string LOGIN_PATH = '/connexion';
    private const string LOGIN_BUTTON_LABEL = 'Se connecter';
    public function testSuccessLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::LOGIN_PATH);
        $form = $crawler->selectButton(self::LOGIN_BUTTON_LABEL)->form([
            'email' => 'user@test.fr',
            'password' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/');
    }
    public function testFailLoginUserAccountNotActivated(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::LOGIN_PATH);
        $form = $crawler->selectButton(self::LOGIN_BUTTON_LABEL)->form([
            'email' => 'notUser@test.fr',
            'password' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects(self::LOGIN_PATH);
        $client->followRedirect();
        $this->assertEquals(1, $client->getCrawler()->filter('.alert-danger')->count());
    }

    public function testFailLoginBadPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::LOGIN_PATH);
        $form = $crawler->selectButton(self::LOGIN_BUTTON_LABEL)->form([
            'email' => 'user@test.fr',
            'password' => 'passwords',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects(self::LOGIN_PATH);
        $client->followRedirect();
        $this->assertEquals(1, $client->getCrawler()->filter('.alert')->count());
    }
}
