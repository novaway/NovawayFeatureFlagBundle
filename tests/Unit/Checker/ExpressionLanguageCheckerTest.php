<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Checker;

use Novaway\Bundle\FeatureFlagBundle\Checker\ExpressionLanguageChecker;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ExpressionLanguageCheckerTest extends TestCase
{
    private ExpressionLanguage $expressionLanguage;
    private AuthenticationTrustResolverInterface $authenticationTrustResolver;
    private RoleHierarchyInterface $roleHierarchy;
    private TokenStorageInterface $tokenStorage;
    private AuthorizationCheckerInterface $authorizationChecker;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->expressionLanguage = $this->createMock(ExpressionLanguage::class);
        $this->authenticationTrustResolver = $this->createMock(AuthenticationTrustResolverInterface::class);
        $this->roleHierarchy = $this->createMock(RoleHierarchyInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testShouldThrowExceptionBecauseExpressionLanguageIsNotSet()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/security-bundle" library must be installed.');

        $this->roleHierarchy->expects($this->never())->method('getReachableRoleNames')->with([]);

        $this->tokenStorage->expects($this->never())->method('getToken');

        $this->logger->expects($this->never())->method('info');

        $this->expressionLanguage->expects($this->never())->method('evaluate');

        $checker = new ExpressionLanguageChecker(
            null,
            $this->authenticationTrustResolver,
            $this->roleHierarchy,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->logger
        );
        $checker->isGranted('is_granted(\'ROLE_ADMIN\')');
    }

    public function testShouldThrowExceptionBecauseTokenStorageIsNotSet()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/security-bundle" library must be installed.');

        $this->roleHierarchy->expects($this->never())->method('getReachableRoleNames')->with([]);

        $this->tokenStorage->expects($this->never())->method('getToken');

        $this->logger->expects($this->never())->method('info');

        $this->expressionLanguage->expects($this->never())->method('evaluate');

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            $this->roleHierarchy,
            null,
            $this->authorizationChecker,
            $this->logger
        );
        $checker->isGranted('is_granted(\'ROLE_ADMIN\')');
    }

    public function testShouldThrowExceptionBecauseAuthenticationTrustResolverIsNotSet()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/security-bundle" library must be installed.');

        $this->roleHierarchy->expects($this->never())->method('getReachableRoleNames')->with([]);

        $this->tokenStorage->expects($this->never())->method('getToken');

        $this->logger->expects($this->never())->method('info');

        $this->expressionLanguage->expects($this->never())->method('evaluate');

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            null,
            $this->roleHierarchy,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->logger
        );
        $checker->isGranted('is_granted(\'ROLE_ADMIN\')');
    }

    public function testShouldGrantedWhenCurrentUserIsNotSet()
    {
        $this->roleHierarchy
            ->expects($this->never())
            ->method('getReachableRoleNames')
            ->with([])
        ;

        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn(null);

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->willReturnCallback(function (string $message, array $context) {
                $this->assertSame('Evaluate expression Language', $message);
                $this->assertCount(2, $context);
                $this->assertArrayHasKey('expression', $context);
                $this->assertSame('is_granted(\'ROLE_ADMIN\')', $context['expression']);
                $this->assertArrayHasKey('variable', $context);

                $variables = $context['variable'];
                $this->assertIsArray($variables);
                $this->assertCount(5, $variables);
                $this->assertArrayHasKey('trust_resolver', $variables);
                $this->assertSame($this->authenticationTrustResolver, $variables['trust_resolver']);
                $this->assertArrayHasKey('auth_checker', $variables);
                $this->assertSame($this->authorizationChecker, $variables['auth_checker']);
                $this->assertArrayHasKey('token', $variables);
                $this->assertInstanceOf(NullToken::class, $variables['token']);
                $this->assertArrayHasKey('user', $variables);
                $this->assertEmpty($variables['user']);
                $this->assertArrayHasKey('roles', $variables);
                $this->assertSame([], $variables['roles']);
            })
        ;

        $this->expressionLanguage
            ->expects($this->once())
            ->method('evaluate')
            ->willReturnCallback(function (string $expression, array $variables) {
                $this->assertSame('is_granted(\'ROLE_ADMIN\')', $expression);
                $this->assertCount(5, $variables);
                $this->assertArrayHasKey('trust_resolver', $variables);
                $this->assertSame($this->authenticationTrustResolver, $variables['trust_resolver']);
                $this->assertArrayHasKey('auth_checker', $variables);
                $this->assertSame($this->authorizationChecker, $variables['auth_checker']);
                $this->assertArrayHasKey('token', $variables);
                $this->assertInstanceOf(NullToken::class, $variables['token']);
                $this->assertArrayHasKey('user', $variables);
                $this->assertEmpty($variables['user']);
                $this->assertArrayHasKey('roles', $variables);
                $this->assertSame([], $variables['roles']);

                return 0;
            })
        ;

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            null,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->logger
        );
        $this->assertFalse($checker->isGranted('is_granted(\'ROLE_ADMIN\')'));
    }

    public function testShouldGrantedWhenCurrentUserIsNotSetAndWithOutLogger()
    {
        $this->roleHierarchy
            ->expects($this->never())
            ->method('getReachableRoleNames')
            ->with([])
        ;

        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn(null);

        $this->logger->expects($this->never())->method('info');

        $this->expressionLanguage
            ->expects($this->once())
            ->method('evaluate')
            ->willReturnCallback(function (string $expression, array $variables) {
                $this->assertSame('is_granted(\'ROLE_ADMIN\')', $expression);
                $this->assertCount(5, $variables);
                $this->assertArrayHasKey('trust_resolver', $variables);
                $this->assertSame($this->authenticationTrustResolver, $variables['trust_resolver']);
                $this->assertArrayHasKey('auth_checker', $variables);
                $this->assertSame($this->authorizationChecker, $variables['auth_checker']);
                $this->assertArrayHasKey('token', $variables);
                $this->assertInstanceOf(NullToken::class, $variables['token']);
                $this->assertArrayHasKey('user', $variables);
                $this->assertEmpty($variables['user']);
                $this->assertArrayHasKey('roles', $variables);
                $this->assertSame([], $variables['roles']);

                return 0;
            })
        ;

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            null,
            $this->tokenStorage,
            $this->authorizationChecker,
            null
        );
        $this->assertFalse($checker->isGranted('is_granted(\'ROLE_ADMIN\')'));
    }

    public function testShouldGrantedCurrentUserWithOutRoleHierarchy(): void
    {
        $user = $this->createMock(UserInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $token->expects($this->once())->method('getRoleNames')->willReturn(['ROLE_SUPER_ADMIN']);
        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $this->roleHierarchy
            ->expects($this->never())
            ->method('getReachableRoleNames')
            ->with(['ROLE_SUPER_ADMIN'])
        ;

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'Evaluate expression Language',
                [
                    'expression' => 'is_granted(\'ROLE_ADMIN\')',
                    'variable' => [
                        'trust_resolver' => $this->authenticationTrustResolver,
                        'auth_checker' => $this->authorizationChecker,
                        'token' => $token,
                        'user' => $user,
                        'roles' => ['ROLE_SUPER_ADMIN'],
                    ],
                ]
            )
        ;

        $this->expressionLanguage
            ->expects($this->once())
            ->method('evaluate')
            ->with(
                'is_granted(\'ROLE_ADMIN\')',
                [
                    'trust_resolver' => $this->authenticationTrustResolver,
                    'auth_checker' => $this->authorizationChecker,
                    'token' => $token,
                    'user' => $user,
                    'roles' => ['ROLE_SUPER_ADMIN'],
                ]
            )
            ->willReturn(1)
        ;

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            null,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->logger
        );
        $this->assertTrue($checker->isGranted('is_granted(\'ROLE_ADMIN\')'));
    }

    public function testShouldGrantedCurrentUserWithOutRoleHierarchyAndWithOutLogger(): void
    {
        $user = $this->createMock(UserInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $token->expects($this->once())->method('getRoleNames')->willReturn(['ROLE_SUPER_ADMIN']);
        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $this->roleHierarchy
            ->expects($this->never())
            ->method('getReachableRoleNames')
            ->with(['ROLE_SUPER_ADMIN'])
        ;

        $this->logger->expects($this->never())->method('info');

        $this->expressionLanguage
            ->expects($this->once())
            ->method('evaluate')
            ->with(
                'is_granted(\'ROLE_ADMIN\')',
                [
                    'trust_resolver' => $this->authenticationTrustResolver,
                    'auth_checker' => $this->authorizationChecker,
                    'token' => $token,
                    'user' => $user,
                    'roles' => ['ROLE_SUPER_ADMIN'],
                ]
            )
            ->willReturn(1)
        ;

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            null,
            $this->tokenStorage,
            $this->authorizationChecker,
            null
        );
        $this->assertTrue($checker->isGranted('is_granted(\'ROLE_ADMIN\')'));
    }

    public function testShouldGrantedCurrentUserWithRoleHierarchy(): void
    {
        $user = $this->createMock(UserInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $token->expects($this->once())->method('getRoleNames')->willReturn(['ROLE_SUPER_ADMIN']);
        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $this->roleHierarchy
            ->expects($this->once())
            ->method('getReachableRoleNames')
            ->with(['ROLE_SUPER_ADMIN'])
            ->willReturn(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'])
        ;

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'Evaluate expression Language',
                [
                    'expression' => 'is_granted(\'ROLE_ADMIN\')',
                    'variable' => [
                        'trust_resolver' => $this->authenticationTrustResolver,
                        'auth_checker' => $this->authorizationChecker,
                        'token' => $token,
                        'user' => $user,
                        'roles' => ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'],
                    ],
                ]
            )
        ;

        $this->expressionLanguage
            ->expects($this->once())
            ->method('evaluate')
            ->with(
                'is_granted(\'ROLE_ADMIN\')',
                [
                    'trust_resolver' => $this->authenticationTrustResolver,
                    'auth_checker' => $this->authorizationChecker,
                    'token' => $token,
                    'user' => $user,
                    'roles' => ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'],
                ]
            )
            ->willReturn(1)
        ;

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            $this->roleHierarchy,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->logger
        );
        $this->assertTrue($checker->isGranted('is_granted(\'ROLE_ADMIN\')'));
    }

    public function testShouldGrantedCurrentUserWithRoleHierarchyAndWithOutLogger(): void
    {
        $user = $this->createMock(UserInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $token->expects($this->once())->method('getRoleNames')->willReturn(['ROLE_SUPER_ADMIN']);
        $this->tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $this->roleHierarchy
            ->expects($this->once())
            ->method('getReachableRoleNames')
            ->with(['ROLE_SUPER_ADMIN'])
            ->willReturn(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'])
        ;

        $this->logger->expects($this->never())->method('info');

        $this->expressionLanguage
            ->expects($this->once())
            ->method('evaluate')
            ->with(
                'is_granted(\'ROLE_ADMIN\')',
                [
                    'trust_resolver' => $this->authenticationTrustResolver,
                    'auth_checker' => $this->authorizationChecker,
                    'token' => $token,
                    'user' => $user,
                    'roles' => ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'],
                ]
            )
            ->willReturn(1)
        ;

        $checker = new ExpressionLanguageChecker(
            $this->expressionLanguage,
            $this->authenticationTrustResolver,
            $this->roleHierarchy,
            $this->tokenStorage,
            $this->authorizationChecker,
            null
        );
        $this->assertTrue($checker->isGranted('is_granted(\'ROLE_ADMIN\')'));
    }
}
