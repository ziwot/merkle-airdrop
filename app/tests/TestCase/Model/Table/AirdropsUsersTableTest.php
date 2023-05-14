<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirdropsUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirdropsUsersTable Test Case
 */
class AirdropsUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirdropsUsersTable
     */
    protected $AirdropsUsers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.AirdropsUsers',
        'app.Airdrops',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AirdropsUsers') ? [] : ['className' => AirdropsUsersTable::class];
        $this->AirdropsUsers = $this->getTableLocator()->get('AirdropsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AirdropsUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirdropsUsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\AirdropsUsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
