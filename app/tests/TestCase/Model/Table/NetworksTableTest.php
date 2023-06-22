<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NetworksTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NetworksTable Test Case
 */
class NetworksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NetworksTable
     */
    protected $Networks;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Networks',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Networks') ? [] : ['className' => NetworksTable::class];
        $this->Networks = $this->getTableLocator()->get('Networks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Networks);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\NetworksTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
