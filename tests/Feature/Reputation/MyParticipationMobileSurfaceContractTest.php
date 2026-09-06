<?php

namespace Tests\Feature\Reputation;

use Tests\TestCase;

class MyParticipationMobileSurfaceContractTest extends TestCase
{
    public function test_six_legacy_participation_tabs_have_mobile_native_card_adapters(): void
    {
        $view = file_get_contents(resource_path('views/history/index.blade.php'));

        $this->assertStringContainsString('contributionMobileSchemas', $view);
        $this->assertStringContainsString("'tab-posts'", $view);
        $this->assertStringContainsString("'tab-comments'", $view);
        $this->assertStringContainsString("'tab-replies'", $view);
        $this->assertStringContainsString("'tab-reactions'", $view);
        $this->assertStringContainsString("'tab-polls'", $view);
        $this->assertStringContainsString("'tab-votes'", $view);
        $this->assertStringContainsString('contribution-mobile-cards', $view);
        $this->assertStringContainsString('contribution-mobile-card', $view);
        $this->assertStringContainsString('role="list"', $view);
        $this->assertStringContainsString('role="listitem"', $view);
    }

    public function test_mobile_breakpoint_hides_legacy_tables_and_keeps_navigation_touch_friendly_without_horizontal_scroll(): void
    {
        $view = file_get_contents(resource_path('views/history/index.blade.php'));

        $this->assertStringContainsString('@media (max-width: 640px)', $view);
        $this->assertStringContainsString('.mobile-adapted-participation .data-table-wrapper', $view);
        $this->assertStringContainsString('display: none;', $view);
        $this->assertStringContainsString('.contribution-mobile-cards', $view);
        $this->assertStringContainsString('.tabs-navigation', $view);
        $this->assertStringContainsString('grid-template-columns: repeat(2, minmax(0, 1fr));', $view);
        $this->assertStringContainsString('min-height: 44px;', $view);
        $this->assertStringContainsString('min-width: 0;', $view);
    }
}
