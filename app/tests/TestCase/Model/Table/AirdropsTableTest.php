<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirdropsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirdropsTable Test Case
 */
class AirdropsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirdropsTable
     */
    protected $Airdrops;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Airdrops',
        'app.Tokens',
        'app.Recipients',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Airdrops') ? [] : ['className' => AirdropsTable::class];
        $this->Airdrops = $this->getTableLocator()->get('Airdrops', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Airdrops);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirdropsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\AirdropsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
