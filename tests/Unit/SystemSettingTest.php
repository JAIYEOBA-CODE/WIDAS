<?php

namespace Tests\Unit;

use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_setting_value(): void
    {
        SystemSetting::create([
            'key' => 'test_key',
            'value' => 'test_value',
            'group' => 'test',
        ]);

        $value = SystemSetting::getValue('test_key');
        $this->assertEquals('test_value', $value);
    }

    public function test_returns_default_when_key_not_found(): void
    {
        $value = SystemSetting::getValue('non_existent', 'default');
        $this->assertEquals('default', $value);
    }

    public function test_can_set_setting_value(): void
    {
        SystemSetting::setValue('dynamic_key', 'dynamic_value', 'dynamic_group');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'dynamic_key',
            'value' => 'dynamic_value',
            'group' => 'dynamic_group',
        ]);
    }

    public function test_scope_by_group(): void
    {
        SystemSetting::factory()->count(3)->create(['group' => 'security']);
        SystemSetting::factory()->count(2)->create(['group' => 'general']);

        $this->assertEquals(3, SystemSetting::byGroup('security')->count());
        $this->assertEquals(2, SystemSetting::byGroup('general')->count());
    }
}
