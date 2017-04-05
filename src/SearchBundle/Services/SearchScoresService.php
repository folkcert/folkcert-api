<?php

namespace SearchBundle\Services;

use Symfony\Component\Yaml\Parser;
use Doctrine\ORM\EntityManager;

/**
 * Search
 */
class SearchScoresService
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $_entityManager;

    /**
     * @var Kernel
     */
    private $_kernel;

    /**
     * @var Array
     */
    private $_config;

    /**
     * @var Array
     */
    private $_irrelevantWords = array(
        'los', 'las', 'sus', 'con', 'vivo', 'live'
    );

    public function __construct(EntityManager $entityManager, $kernel)
    {
        $this->_entityManager = $entityManager;
        $this->_kernel = $kernel;
    }

    /**
     * 
     * @param $entity string
     * @param $query string
     * @return array
     */
    public function search($entity, $query)
    {
        /* Load bundle config file */
        $this->_config = $this->_loadConfigFile();

        /* Load specific config for this entity */
        $this->_entityConfig = $this->_getEntityConfig($entity);

        /* Limit the query */
        $query = $this->_limitQueryLength($query);

        /* Lowercase the query */
        $query = strtolower($query);

        /* Convert the query string into an array of keywords */
        $keywordsArray = $this->_getKeywordsArray($query);

        /* Get query builder */
        $qb = $this->_getQueryBuilder($entity, $query, $keywordsArray);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get the Configuration
     * @return array
     */
    private function _loadConfigFile()
    {
        $yaml = new Parser();
        $path = $this->_kernel->locateResource('@SearchBundle/Resources/config/config.yml');

        $value = $yaml->parse(file_get_contents($path));

        return $value;
    }

    /**
     * Get the Configuration for an entity
     * @param $entity string
     * @return array
     */
    private function _getEntityConfig($entity)
    {
        return $this->_config['entities'][$entity];
    }

    /**
     * Limits the query length
     * @param $query string
     * @return string
     */
    private function _limitQueryLength($query)
    {
        return substr($query, 0, $this->_getConfigParameter('queryStringLengthLimit'));
    }

    /**
     * Converts the query string into an array of relevant words
     * @param $entity string
     * @return array
     */
    private function _getKeywordsArray($query)
    {
        /* Convert string to array */
        $keywordsArray = explode(' ', $query);

        /* Remove words with N letters or less */
        $keywordsArray = array_filter($keywordsArray, array($this, '_isShortString'));

        /* Remove irrelevant words */
        $keywordsArray = array_filter($keywordsArray, array($this, '_isRelevantWord'));

        /* Lowercase keywords */
        $keywordsArray = array_map('strtolower', $keywordsArray);

        /* Limit the array size to N words */
        $keywordsArray = array_slice($keywordsArray, 0, $this->_getConfigParameter('keywordsArrayLimit'));

        return $keywordsArray;
    }

    /**
     * Check if the string is long enough to be into the search
     * @param $string string
     * @return boolean
     */
    private function _isShortString($string)
    {
        return strlen($string) >= $this->_getConfigParameter('keywordMinLength');
    }

    /**
     * Check if the string is not in the list of irrelevant words
     * @param $string string
     * @return boolean
     */
    private function _isRelevantWord($string)
    {
        return !in_array($string, $this->_irrelevantWords);
    }

    /**
     * Returns the QueryBuilder object with the query to execute
     * @param $entity string
     * @param $query string
     * @param $keywordsArray array
     * @return QueryBuilder
     */
    private function _getQueryBuilder($entity, $query, $keywordsArray)
    {
        /* Get the scores configuration for the current Entity */
        $scoresArray = $this->_entityConfig['scores'];

        $qb = $this->_entityManager->createQueryBuilder();

        /* Create the query builder */
        $qb->select('e as entry')
            ->from($entity, 'e');

        /* Join tables */
        if (!empty($this->_entityConfig['join'])) {
            foreach ($this->_entityConfig['join'] as $alias => $table) {
                $qb->join($table, $alias);
            }
        }

        $scoresQueryArray = array();
        
        /* Adds the full coincidence scores */
        foreach ($scoresArray['full'] as $attr => $score) {
            $scoresQueryArray[] = "(CASE WHEN LOWER({$attr}) LIKE '%{$query}%' THEN {$score} ELSE 0 END)";
        }

        /* Add the partial coincidence scores */
        foreach ($keywordsArray as $index => $keyword) {
            $keyword = strtolower($keyword);
            foreach ($scoresArray['partial'] as $attr => $score) {
                $scoresQueryArray[] =
                    '(CASE WHEN ' . $qb->expr()->like($qb->expr()->lower($attr), $qb->expr()->literal('%' . $keyword . '%'))
                    . ' THEN ' . $qb->expr()->literal($score) . ' ELSE 0 END)';
            }
        }

        $scoreString = implode(' + ', $scoresQueryArray);
        $qb->addSelect($scoreString . ' as relevance');

        $qb->where($qb->expr()->gte($scoreString, $this->_getConfigParameter('minRelevanceResult')));
        $qb->orderBy('relevance', 'DESC');

        return $qb;
    }

    /**
     * Returns the value of the config parameter
     * @param $string parameter
     * @return string
     */
    private function _getConfigParameter($parameter)
    {
        return $this->_config['config'][$parameter];
    }
}
