<?php

declare(strict_types=1);

namespace App\Tests\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticatorTest extends TestCase
{
    /** @var MockObject|UserRepository */
    private MockObject|UserRepository $userRepository;

    /** @var LoginFormAuthenticator  */
    private LoginFormAuthenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()->getMock();
        $urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        /* @var MockObject|UrlMatcherInterface $urlMatcher */
        $urlMatcher = $this->createMock(UrlMatcherInterface::class);
        $urlMatcher->expects($this->any())->method('match')->willReturn([]);
        $this->authenticator = new LoginFormAuthenticator(
            $urlGenerator,
            $this->userRepository,
            $this->createMock(UrlMatcherInterface::class),
            $eventDispatcher,
        );
    }
    public function testPassportAuthentication(): void
    {
        $request = new Request([], ['email' => 'user@test.fr']);

        $user = new User();
        $this->userRepository
            ->expects($this->once())
            ->method('findOneByEmail')
            ->with('user@test.fr')
            ->willReturn(new User())
        ;

        $passport = $this->authenticator->authenticate($request);
        $this->assertEquals($passport->getUser(), $user);
        $this->assertTrue($passport->hasBadge(CsrfTokenBadge::class));
        $this->assertTrue($passport->hasBadge(PasswordCredentials::class));
    }
}
