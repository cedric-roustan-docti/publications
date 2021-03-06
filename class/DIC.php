<?php

/**
 * Use to manage all dependency injection system and manage autoload
 */
class DIC {
	/** @var array All params stored */
	protected $params = array();
	/** @var \Interfaces\Shared[] Shared objects instance */
	protected $objects_cache = array();
	/** @var \Interfaces\Object[] No-shared objects instance to use same object for all get on same ident and id */
	protected $shared_instances = array();
	/** @var array[] declaration of alias and class name */
	protected $object_definitions = array(
		'project_object'     => array(
			'shared'     => false,
			'class_name' => '\\Object\\Project',
		),
		'publication_object' => array(
			'shared'     => false,
			'class_name' => '\\Object\\Publication',
		),
		'row_object'         => array(
			'shared'     => false,
			'class_name' => '\\Object\\Row',
		),
		'issue_object'        => array(
			'shared'     => false,
			'class_name' => '\\Object\\Issue\\Jira',
		),

		'database'           => array(
			'shared'     => true,
			'class_name' => '\\Shared\\Database',
		),
		'project'            => array(
			'shared'     => true,
			'class_name' => '\\Shared\\Project',
		),
		'config'             => array(
			'shared'     => true,
			'class_name' => '\\Shared\\Config',
		),
		'form_utils'         => array(
			'shared'     => true,
			'class_name' => '\\Shared\\FormUtils',
		),
		'publication'        => array(
			'shared'     => true,
			'class_name' => '\\Shared\\Publication',
		),
		'vcs'         => array(
			'shared'     => true,
			'class_name' => '\\Shared\\VCS\\Subversion',
		),
		'issue'               => array(
			'shared'     => true,
			'class_name' => '\\Shared\\Issue\\Jira',
		),
	);

	/**
	 * Private construct to singleton
	 *
	 * @param array $params Some params stored into DIC
	 * @throws Exception
	 */
	public function __construct($params = array()) {
		if (!is_array($params)) {
			throw new Exception('$param must be an array : "'.var_export($params, true).'"');
		}

		spl_autoload_register(array($this, 'autoload'));
		$this->params = $params;
	}

	/**
	 * Autoload class function
	 *
	 * @param    string $class_name
	 */
	public function autoload($class_name) {
		$file = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $class_name).'.php';
		if (file_exists($file)) {
			require $file;
		}
	}

	/**
	 * Store a parameter
	 *
	 * @param string $param
	 * @param mixed  $value
	 */
	public function setParam($param, $value) {
		$this->params[$param] = $value;
	}

	/**
	 * Get stored parameter. Return null if not exists
	 *
	 * @param string $param
	 * @return mixed
	 */
	public function getParam($param) {
		return isset($this->params[$param]) ? $this->params[$param] : null;
	}

	/**
	 * Allow new object (or replace existant one) with new definition
	 *
	 * @param    string $ident
	 * @param    string $class_name    Class name
	 * @param    bool   $shared
	 */
	public function setObjectDefinition($ident, $class_name, $shared = false) {
		if ($ident == 'dic') {
			return;
		}

		$class_name                       = substr($class_name, 0, 1) === '\\' ? $class_name : '\\'.$class_name;
		$this->object_definitions[$ident] = array(
			'shared'     => (bool)$shared,
			'class_name' => $class_name
		);
	}

	/**
	 * Instance an object
	 *
	 * @param string $ident
	 * @return \Interfaces\Object
	 * @throws Exception
	 */
	protected function instanceObject($ident) {
		$class_name = $this->object_definitions[$ident]['class_name'];
		if (!class_exists($class_name)) {
			throw new Exception('Class for object does not exists : "'.$class_name.'" for ident : "'.$ident.'"');
		}

		/** @var \Interfaces\Object $object */
		$object = new $class_name();
		if (!($object instanceof \Interfaces\Object)) {
			throw new Exception('Instance does not implements \Interfaces\Object  : '.get_class($object));
		}

		return $object;
	}

	/**
	 * Instance a Shared object (singleton)
	 *
	 * @param string $ident
	 * @return \Interfaces\Shared
	 * @throws Exception
	 */
	protected function getSharedObject($ident) {
		if (isset($this->shared_instances[$ident])) {
			return $this->shared_instances[$ident];
		}

		$object = $this->instanceObject($ident);
		if (!$object->isValid()) {
			throw new Exception('Shared object invalid  : '.get_class($object));
		}

		$this->shared_instances[$ident] = $object;

		return $object;
	}

	/**
	 * Instance an object
	 *
	 * @param string     $ident
	 * @param int|string $object_id
	 * @return \Interfaces\Object
	 * @throws Exception
	 */
	public function getObject($ident, $object_id = 0) {
		if (!isset($this->object_definitions[$ident])) {
			throw new Exception('Invalid object ident : "'.$ident.'"');
		}

		if ($this->object_definitions[$ident]['shared']) {
			$object = $this->getSharedObject($ident);
		} else {
			$cache_key = $ident.'|'.$object_id;
			if ($object_id && isset($this->objects_cache[$cache_key])) {
				return $this->objects_cache[$cache_key];
			}
			$object = $this->instanceObject($ident);
		}

		$object->setDic($this);
		foreach ($object->getDependenciesList() as $dep_ident) {
			$dep_object = $this->getObject($dep_ident);
			if ($dep_object instanceof \Interfaces\Shared) {
				$object->addDependenceObject($dep_ident, $dep_object);
			}
		}

		$object->initialize();

		if ($object_id && !$this->object_definitions[$ident]['shared']) {
			$object->initializeId($object_id);
		}

		if (!$object->isValid()) {
			$object = null;
		}

		if ($object_id && !$this->object_definitions[$ident]['shared']) {
			$this->objects_cache[$cache_key] = $object;
		}

		return $object;
	}
}
