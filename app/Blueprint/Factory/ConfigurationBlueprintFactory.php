<?php namespace Rancherize\Blueprint\Factory;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Exceptions\BlueprintNotFoundException;
use Rancherize\Configuration\Configurable;

/**
 * Class ConfigurationBlueprintFactory
 * @package Rancherize\Blueprint\Factory
 *
 * This blueprint factory uses the rancherize.json to store the blueprints
 */
class ConfigurationBlueprintFactory implements BlueprintFactory  {
	/**
	 * @var Configurable
	 */
	private $configuration;

	/**
	 * ConfigurationBlueprintFactory constructor.
	 * @param Configurable $configuration
	 */
	public function __construct(Configurable $configuration) {
		$this->configuration = $configuration;
		$this->add('webserver', 'Rancherize\Blueprint\Webserver\WebserverBlueprint');
	}

	/**
	 * @param string $name
	 * @param string $classpath
	 */
	public function add(string $name, string $classpath) {
		$this->configuration->set('project.blueprints.'.$name, $classpath);
	}

	/**
	 * @param string $name
	 * @return Blueprint
	 * @throws BlueprintNotFoundException
	 */
	public function get(string $name) : Blueprint {
		$blueprints = $this->getBlueprints();
		if( !array_key_exists($name, $blueprints) )
			throw new BlueprintNotFoundException($name);

		$blueprintClasspath = $blueprints[$name];

		return new $blueprintClasspath;
	}

	/**
	 * @return array
	 */
	protected function getBlueprints() {
		return $this->configuration->get('project.blueprints');
	}

	/**
	 * @return string[]
	 */
	public function available() : array {
		$blueprints = $this->getBlueprints();

		$names = array_keys($blueprints);

		return $names;
	}
}