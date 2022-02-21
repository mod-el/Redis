<?php namespace Model\Redis;

use Model\Core\Module_Config;

class Config extends Module_Config
{
	/**
	 */
	protected function assetsList()
	{
		$this->addAsset('config', 'config.php', function () {
			return '<?php
$config = [
	\'cluster\' => false,
	\'host\' => \'127.0.0.1\',
	\'port\' => 6379,
	\'password\' => null,
	\'prefix\' => null,
];
';
		});
	}

	public function getConfigData(): ?array
	{
		return [
			'cluster' => ['label' => 'Cluster (y/n)', 'default' => 0],
			'host' => ['label' => 'Host', 'default' => '127.0.0.1'],
			'port' => ['label' => 'Port', 'default' => 6379],
			'password' => ['label' => 'Password', ' defalut' => null],
			'prefix' => ['label' => 'Prefix', ' defalut' => null],
		];
	}
}
