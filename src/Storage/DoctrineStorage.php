<?php

declare(strict_types=1);

namespace Mezzio\DebugBar\Storage;

use DebugBar\Storage\PdoStorage;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

use function count;
use function implode;
use function is_string;
use function serialize;
use function unserialize;

/**
 * Stores collected data into a database using Doctrine
 */
class DoctrineStorage extends PdoStorage
{
    protected EntityManagerInterface $entityManager;

    protected bool $saveSqlQueriesToExtraTable;

    protected array $sqlQueriesExtra = [
        'extra_table' => 'INSERT INTO `phpdebugbar_sql` 
        (`requestId`, `query`, `params`, `duration`, `duration_str`) VALUES (?,?,?,?,?)',
    ];

    public function __construct(EntityManagerInterface $entityManager, array $config)
    {
        $this->entityManager              = $entityManager;
        $this->tableName                  = $config['tableName'] ?? 'phpdebugbar';
        $this->saveSqlQueriesToExtraTable = $config['save_sql_queries_to_extra_table'] ?? false;
        $this->setSqlQueries($this->sqlQueriesExtra);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $id
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public function save($id, $data)
    {
        $meta = $data['__meta'];
        $this->entityManager->getConnection()->executeStatement(
            $this->getSqlQuery('save'),
            [
                $id,
                serialize($data),
                $meta['utime'],
                $meta['datetime'],
                $meta['uri'],
                $meta['ip'],
                $meta['method'],
            ]
        );
        if ($this->saveSqlQueriesToExtraTable) {
            $this->saveSqlQueries($id, $data[ 'doctrine' ][ 'statements' ] ?? null);
        }
    }

    /**
     * @throws Exception
     */
    protected function saveSqlQueries(string $requestId, ?array $statements): void
    {
        if (empty($statements)) {
            return;
        }
        foreach ($statements as $statement) {
            $this->entityManager->getConnection()->executeStatement(
                $this->getSqlQuery('extra_table'),
                [
                    $requestId,
                    $statement['sql'],
                    serialize($statement['params']),
                    $statement['duration'],
                    $statement['duration_str'],
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $id
     * @return array<string, mixed>|null
     * @throws Exception
     */
    public function get($id)
    {
        $data = $this->entityManager->getConnection()
            ->executeQuery($this->getSqlQuery('get'), [$id])
            ->fetchFirstColumn();

        $serialized = $data[0] ?? null;
        if (is_string($serialized)) {
            return unserialize($serialized);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function find(array $filters = [], $max = 20, $offset = 0)
    {
        $where  = [];
        $params = [];
        foreach ($filters as $key => $value) {
            $where[]  = "meta_$key = ?";
            $params[] = $value;
        }
        if (count($where)) {
            $where = " WHERE " . implode(' AND ', $where);
        } else {
            $where = '';
        }

        $sql = $this->getSqlQuery('find', [
            'where'  => $where,
            'offset' => $offset,
            'limit'  => $max,
        ]);

        $res = $this->entityManager->getConnection()->executeQuery($sql, $params);

        $results = [];
        foreach ($res->fetchAllAssociative() as $row) {
            $data      = unserialize($row['data']);
            $results[] = $data['__meta'];
            unset($data);
        }
        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function clear()
    {
        $this->entityManager->getConnection()->executeStatement($this->getSqlQuery('clear'));
    }
}
