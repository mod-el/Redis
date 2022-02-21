<?php namespace Model\Redis;

use Model\Core\Module;

class Redis extends Module
{
	private \RedisCluster|\Redis $redis;

	/**
	 * Simil-factory pattern per connettersi una volta a Redis
	 *
	 * @return \RedisCluster|\Redis|null
	 */
	private function getClient(): \RedisCluster|\Redis|null
	{
		if (!isset($this->redis)) {
			$config = $this->retrieveConfig();
			if ($config['host'] === 'session') // For development purposes
				return null;

			if ($config['cluster']) {
				$this->redis = new \RedisCluster(null, [$config['host'] . ':' . $config['port']]);
			} else {
				$this->redis = new \Redis();
				$this->redis->connect($config['host'], $config['port']);
			}

			if ($config['password'] ?? null)
				$this->redis->auth($config['password']);
		}

		return $this->redis;
	}

	/**
	 * Scorciatoia per tutti i metodi di Redis
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call(string $name, array $arguments): mixed
	{
		$config = $this->retrieveConfig();

		if ($config['host'] === 'session') { // For development purposes
			switch ($name) {
				case 'get':
					return $_SESSION['redis:' . $arguments[0]] ?? null;

				case 'set':
					$_SESSION['redis:' . $arguments[0]] = $arguments[1];
					return true;
			}
		}

		if (!empty($arguments[0]) and $config['prefix'] ?? null)
			$arguments[0] = $config['prefix'] . ':' . $arguments[0];

		return call_user_func_array([$this->getClient(), $name], $arguments);
	}
}
