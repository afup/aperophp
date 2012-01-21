<?php

namespace Aperophp\Provider\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 *	Model service
 * 
 * 	@author Mikael Randy <mikael.randy@gmail.com>
 */
class Model implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// *******
		// ** Model loading
		// *******
		$app['model'] = $app->protect(function ($tableName) use ($app) 
		{
			$class = sprintf('PrestaQuotes\Model\%s', $tableName);
			
			if( !class_exists($class) )
			{
				throw new \InvalidArgumentException(sprintf('"%s" class does not exists', $class));
			}

			return new $class($app['db']);
		});
	}
}