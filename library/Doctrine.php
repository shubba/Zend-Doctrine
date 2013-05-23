<?php

class App_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;
 
    public function init()
    {
        return $this->getEntityManager();
    }
 
    public function getEntityManager()
    {
        if (null === $this->em) {
            $options = $this->getOptions();
            $config = new \Doctrine\ORM\Configuration();
            $config->setProxyDir($options['metadata']['proxyDir']);
            $config->setProxyNamespace('Proxy');
            $config->setAutoGenerateProxyClasses((APPLICATION_ENV == 'development'));
            $driverImpl = $config->newDefaultAnnotationDriver($options['metadata']['entityDir']);
            $config->setMetadataDriverImpl($driverImpl);
            $cache = new \Doctrine\Common\Cache\ArrayCache();
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);
 
            $evm = new \Doctrine\Common\EventManager();
            $this->em = \Doctrine\ORM\EntityManager::create($options['db'],$config,$evm);
            Zend_Registry::set('doctrine', $this->em);
        }
 
        return $this->em;
    }
}


?>
