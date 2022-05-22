<?php
declare(strict_types=1);

namespace Test\Unit\Domain\UseCases;

use PHPUnit\Framework\TestCase;
use App\Domain\UseCases\Transaction\DepositUseCase;
use RuntimeException;
use App\Domain\Entity\Account;
use App\Domain\Entity\Transaction;
use App\Application\Exceptions\ResourceNotFoundException;
use App\Application\Services\AccountService;
use App\Infrastructure\Repositories\TransactionRepository;
use App\Domain\UseCases\UseCaseResponse;

final class DepositUseCaseTest extends TestCase
{
    /**
     * @var MockObject|TransactionRepository
     */
    private mixed $transactionRepository;

    /**
     * @var MockObject|AccountService
     */
    private mixed $accountService;

    /**
     * @var MockObject|Transaction
     */
    private mixed $transaction;

    /**
     * @var MockObject|Account
     */
    private mixed $account;

    protected function setUp(): void
    {
        $this->account                = $this->createMock(Account::class);
        $this->transaction            = $this->createMock(Transaction::class);
        $this->account_service        = $this->createMock(AccountService::class);
        $this->transaction_repository = $this->createMock(TransactionRepository::class);

        $this->main = new DepositUseCase();
        $this->main->setDependencie('account_service', $this->account_service);
        $this->main->setDependencie('transaction_repository',
            $this->transaction_repository
        );

        parent::setUp();
    }

    /**
     * @dataProvider invalidDependenciesProvider
     */
    public function testHandleWithExceptionsInDependencies(
        $accountService, $transactionRepository
    ): void
    {
        $this->main->setDependencie('account_service', $accountService);
        $this->main->setDependencie('transaction_repository',
            $transactionRepository);

        $this->expectException(RuntimeException::class);

        $this->account_service
            ->expects($this->never())
            ->method('find');

        $result = $this->main->handle($this->transaction);
    }

    public function invalidDependenciesProvider(): array
    {
        return [
            'only account service' => [
                'account_service' => true,
                'transaction_repository' => null
            ],
            'only transaction repository' => [
                'account_service' => null,
                'transaction_repository' => true
            ],
            'both invalid first order' => [
                'account_service' => null,
                'transaction_repository' => null
            ],
            'both invalid second order' => [
                'transaction_repository' => null,
                'account_service' => null
            ],
        ];
    }

    public function testGetDependencieKeysRequired(): void
    {
        $this->assertEquals(
            ['account_service', 'transaction_repository'],
            $this->main->getDependencieKeysRequired()
        );
    }

    public function testHandleWithNonExistentDestination(): void
    {
        $amount      = 10;
        $destination = 1;

        $this->transaction
            ->expects($this->atLeast(2))
            ->method('getDestination')
            ->with()
            ->willReturn($destination);

        $this->account_service
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturnCallback(function () {
                throw new ResourceNotFoundException();
            });

        $this->transaction_repository
            ->expects($this->once())
            ->method('beginTransaction')
            ->with();

        $this->transaction
            ->expects($this->once())
            ->method('getAmount')
            ->with()
            ->willReturn($amount);

        $this->account_service
            ->expects($this->once())
            ->method('store')
            ->with([
                'balance' => $amount,
                'id' => $destination
        ]);

        $this->account
            ->expects($this->never())
            ->method('sum');

        $this->transaction_repository
            ->expects($this->never())
            ->method('rollBack');

        $this->transaction_repository
            ->expects($this->once())
            ->method('storeTransaction')
            ->with($this->transaction);

        $this->transaction_repository
            ->expects($this->once())
            ->method('commit')
            ->with();

        $result = $this->main->handle($this->transaction);

        $this->assertInstanceOf(UseCaseResponse::class, $result);
        $this->assertEquals([
            'destination' => [
                'id' => $destination,
                'balance' => $amount
            ]
        ], $result->getContent());
    }

    public function testHandleWithExistentDestination(): void
    {
        $amount      = 10;
        $destination = 1;

        $this->transaction_repository
            ->expects($this->once())
            ->method('beginTransaction')
            ->with();

        $this->transaction
            ->expects($this->once())
            ->method('getDestination')
            ->with()
            ->willReturn($destination);

        $this->account_service
            ->expects($this->once())
            ->method('find')
            ->with($destination)
            ->willReturn($this->account);

        $this->transaction
            ->expects($this->once())
            ->method('getAmount')
            ->with()
            ->willReturn($amount);

        $this->account
            ->expects($this->once())
            ->method('sum')
            ->with($amount);

        $this->account
            ->expects($this->once())
            ->method('toArray')
            ->with()
            ->willReturn([
                'created_at' => 'now()',
                'deleted_at' => 'now()'
        ]);

        $this->account
            ->expects($this->atLeast(2))
            ->method('getId')
            ->with()
            ->willReturn($destination);

        $this->account_service
            ->expects($this->once())
            ->method('updateById')
            ->with($destination, []);

        $this->account
            ->expects($this->once())
            ->method('getBalance')
            ->with()
            ->willReturn($amount);

        $this->account_service
            ->expects($this->never())
            ->method('store');

        $this->transaction_repository
            ->expects($this->never())
            ->method('rollBack');

        $this->transaction_repository
            ->expects($this->once())
            ->method('storeTransaction')
            ->with($this->transaction);

        $this->transaction_repository
            ->expects($this->once())
            ->method('commit')
            ->with();

        $result = $this->main->handle($this->transaction);

        $this->assertInstanceOf(UseCaseResponse::class, $result);
        $this->assertEquals([
            'destination' => [
                'id' => $destination,
                'balance' => $amount
            ]
        ], $result->getContent());
    }
}