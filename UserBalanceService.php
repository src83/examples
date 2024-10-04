<?php
declare(strict_types=1);

/*
У пользователя есть баланс (decimal(10,8))", нужно:
1 - списать баланс
2 - зачислить баланс
*/

class UserBalanceService
{
	private int $userId;
	private string $currentBalance;

	public function __construct(int $userId, string $initCurrentBalance)
	{
		$this->userId = $userId;
		$this->currentBalance = $initCurrentBalance;
	}

	/**
	 * Списывает сумму с баланса пользователя.
	 */
	public function withdraw($amount): void
	{
		if ($amount <= 0) {
			throw new Exception("Сумма списания должна быть положительной!");
		}

		if ($amount > $this->currentBalance) {
			throw new Exception("Сумма списания превышает остаток средств на балансе!");
		}

		$this->currentBalance = bcsub($this->currentBalance, $amount, 8);

		// Если требуется сохранение - понадобится инжектировать репозиторий через конструктор
		// try {
		// 	$this->userBalanceRepository->save($this->userId, $this->currentBalance);
		// } catch (Throwable $e) {
		// 	 error_log("Ошибка при сохранении баланса пользователя ID {$this->userId}: " . $e->getMessage());
		// }
	}

	/**
	 * 	Зачисляет сумму на баланс пользователя.
	 */
	public function deposit($amount): void
	{
		if ($amount <= 0) {
			throw new Exception("Сумма пополнения должна быть положительной!");
		}

		$this->currentBalance = bcadd($this->currentBalance, $amount, 8);

		// Если требуется сохранение - понадобится инжектировать репозиторий через конструктор
		// try {
		// 	$this->userBalanceRepository->save($this->userId, $this->currentBalance);
		// } catch (Throwable $e) {
		// 	 error_log("Ошибка при сохранении баланса пользователя ID {$this->userId}: " . $e->getMessage());
		// }
	}

	public function getCurrentBalance(): string
	{
		return $this->currentBalance;
	}
}




// Client's code...

// Инициализация
$userId = 1;
$initCurrentBalance = '99.99999999';
$userBalanceService = new UserBalanceService($userId, $initCurrentBalance);


// Далее происходит один из двух сценариев (или оба сразу):

// Списание
$deltaSum = '12.34567890';
$userBalanceService->withdraw($deltaSum);
echo $userBalanceService->getCurrentBalance()."\n";  // 87.65432109

// Пополнение
$deltaSum = '34.56789012';
$userBalanceService->deposit($deltaSum);
echo $userBalanceService->getCurrentBalance()."\n";  // 122.22221121
