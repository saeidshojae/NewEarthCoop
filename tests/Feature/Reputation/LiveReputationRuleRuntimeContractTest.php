<?php

namespace Tests\Feature\Reputation;

use Tests\TestCase;

class LiveReputationRuleRuntimeContractTest extends TestCase
{
    public function test_profile_milestone_rules_have_real_runtime_wiring(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Profile/ProfileController.php'));
        $servicePath = app_path('Services/ProfileMilestoneReputationService.php');

        $this->assertFileExists($servicePath);
        $service = file_get_contents($servicePath);

        $this->assertStringContainsString('ProfileMilestoneReputationService', $controller);
        $this->assertStringContainsString('sync', $controller);

        foreach (['profile_photo_uploaded', 'social_links_added', 'documents_uploaded', 'bio_added'] as $action) {
            $this->assertStringContainsString("'{$action}'", $service);
            $this->assertSame('participation', config("reputation.policy_defaults.{$action}.dimension"));
            $this->assertFalse(config("reputation.policy_defaults.{$action}.convertible"));
            $this->assertSame('once_per_context', config("reputation.policy_defaults.{$action}.repeat_policy"));
        }
    }

    public function test_bid_cancellation_rule_is_wired_to_actual_cancellation_transition(): void
    {
        $controller = file_get_contents(base_path('app/Modules/Stock/Controllers/BidController.php'));

        $this->assertStringContainsString("'bid_canceled'", $controller);
        $this->assertStringContainsString("'bid_canceled:bid:'", $controller);
        $this->assertSame('participation', config('reputation.policy_defaults.bid_canceled.dimension'));
        $this->assertFalse(config('reputation.policy_defaults.bid_canceled.convertible'));
        $this->assertSame('once_per_context', config('reputation.policy_defaults.bid_canceled.repeat_policy'));
    }

    public function test_confirmed_moderation_outcomes_drive_report_and_fraud_penalties(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Admin/ReportController.php'));
        $view = file_get_contents(resource_path('views/admin/reports/show.blade.php'));

        $this->assertStringContainsString('applyConfirmedReputationOutcome', $controller);
        $this->assertStringContainsString("'report_received'", $controller);
        $this->assertStringContainsString("'fraud'", $controller);
        $this->assertStringContainsString("'confirmed_fraud'", $controller);
        $this->assertStringContainsString('confirmed_fraud', $view);

        foreach (['report_received', 'fraud'] as $action) {
            $this->assertSame('reliability', config("reputation.policy_defaults.{$action}.dimension"));
            $this->assertFalse(config("reputation.policy_defaults.{$action}.convertible"));
            $this->assertSame('once_per_context', config("reputation.policy_defaults.{$action}.repeat_policy"));
        }
    }

    public function test_only_legacy_election_rules_are_deprecated(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Admin/ReputationController.php'));

        $this->assertStringContainsString("private const DEPRECATED_RULE_KEYS = ['election_candidate', 'election_participated'];", $controller);

        foreach (['profile_photo_uploaded', 'social_links_added', 'documents_uploaded', 'bio_added', 'report_received', 'bid_canceled', 'fraud'] as $action) {
            $this->assertArrayHasKey($action, config('reputation.weights'));
        }
    }
}
