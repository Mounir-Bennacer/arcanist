<?php declare(strict_types=1);

namespace Arcanist\Tests;

use Mockery as m;
use Arcanist\Action\WizardAction;
use Arcanist\Contracts\WizardActionResolver;
use Arcanist\Resolver\ContainerWizardActionResolver;

class ContainerWizardActionResolverTest extends TestCase
{
    /** @test */
    public function it_implements_the_correct_interface(): void
    {
        self::assertInstanceOf(
            WizardActionResolver::class,
            new ContainerWizardActionResolver()
        );
    }

    /** @test */
    public function it_resolves_the_action_from_the_container(): void
    {
        $expected = m::mock(WizardAction::class);
        app()->instance('::action::', $expected);

        $actual = (new ContainerWizardActionResolver())->resolveAction('::action::');

        self::assertEquals($expected, $actual);
    }
}
