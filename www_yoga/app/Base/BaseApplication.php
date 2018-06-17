<?php
namespace App\Base;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DIYmlFileLoader;

use Laravel\Lumen\Application;

use Symfony\Component\Config\FileLocator;

use App\Common\Api;

class BaseApplication extends Application
{

    protected static $fileLocator = null;

	protected $siteRoot = '';	//程序主目录

    protected static $appContainer = null;

	public function __construct($basePath = null)
	{
		$this->initSiteRoot();
		parent::__construct($this->siteRoot);

		$this->initContainer();

		$this->initRouter();
	}

	protected function initSiteRoot() {
		$this->siteRoot = realpath(__DIR__.'/../');
	}

	protected function getConfigPath() {
		return $this->siteRoot . '/config';
	}

	protected function setConfigFileLocator(){
		if(!self::$fileLocator)
			self::$fileLocator = new FileLocator(array($this->getConfigPath()));
	}

	protected function initContainer()
	{

        $this->setConfigFileLocator();
        if(!self::$appContainer) {
            self::$appContainer = new ContainerBuilder();
            $diLoader = new DIYmlFileLoader(self::$appContainer, self::$fileLocator);
            $diLoader->load('api.yml');
            $diLoader->load('app.yml');
        }

        Api::setRouterConfig(self::$appContainer->getParameter('router'));

		$this['container'] = self::$appContainer;
	}

	protected function initRouter() {

		$this->router->group([
			'namespace' => 'App\Http\Controllers',
		], function ($routerInstance) {
			$routerIn  = $this->container->getParameter('router');
			$routerInstance->get('/', 'MainController@index');
			foreach($routerIn as $url => $accessIn) {
				$url = '/'.$url;
				if($accessIn['method'] == 'get') {
					$routerInstance->get($url, $accessIn['controllerAction'] );
				} else if($accessIn['method'] == 'post') {
					$routerInstance->post('/'.$url, $accessIn['controllerAction'] );
				} else if($accessIn['method'] == 'any') {
					$routerInstance->get($url, $accessIn['controllerAction'] );
					$routerInstance->post($url, $accessIn['controllerAction'] );
				}
			}
		});
	}




}